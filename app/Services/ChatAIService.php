<?php

namespace App\Services;

use OpenAI;
use Illuminate\Support\Facades\Log;
use App\Models\ChatMessage;

class ChatAIService
{
    private $client;
    private $model;
    private $maxTokens;

    public function __construct()
    {
        $apiKey = config('services.openai.api_key');
        
        if (!$apiKey) {
            throw new \Exception('OpenAI API key not configured');
        }

        $this->client = OpenAI::client($apiKey);
        $this->model = config('chat.ai_model', 'gpt-3.5-turbo');
        $this->maxTokens = config('chat.ai_max_tokens', 150);
    }

    public function generateResponse(string $message, string $sessionId, array $context = []): string
    {
        try {
            // Get conversation history for context
            $conversationHistory = $this->getConversationHistory($sessionId);
            
            // Build the system prompt
            $systemPrompt = $this->buildSystemPrompt();
            
            // Build messages array for ChatGPT
            $messages = [
                ['role' => 'system', 'content' => $systemPrompt]
            ];
            
            // Add conversation history
            foreach ($conversationHistory as $historyMessage) {
                $role = $historyMessage->sender_type === 'visitor' ? 'user' : 'assistant';
                $messages[] = [
                    'role' => $role,
                    'content' => $historyMessage->message
                ];
            }
            
            // Add current message
            $messages[] = [
                'role' => 'user',
                'content' => $message
            ];

            $response = $this->client->chat()->create([
                'model' => $this->model,
                'messages' => $messages,
                'max_tokens' => $this->maxTokens,
                'temperature' => 0.7,
                'frequency_penalty' => 0.3,
                'presence_penalty' => 0.3,
            ]);

            return trim($response->choices[0]->message->content);
            
        } catch (\Exception $e) {
            Log::error('AI Response Generation Error: ' . $e->getMessage());
            
            // Fallback to predefined responses
            return $this->getFallbackResponse($message);
        }
    }

    private function buildSystemPrompt(): string
    {
        return "You are Masud Rana Mamun's AI assistant on his portfolio website. You represent him professionally and personally to help visitors learn about him.

PERSONAL INFORMATION:
- Full Name: Masud Rana Mamun
- Profession: Full-Stack Developer & Data Analyst
- Education: Final-year Computer Science Engineering (CSE) student
- Current Location: Dhaka, Bangladesh
- Hometown: Rangpur, Bangladesh
- Age: Final year university student (early 20s)

TECHNICAL SKILLS & EXPERTISE:
- Backend: Laravel PHP, Node.js, Python
- Frontend: React, Vue.js, JavaScript, HTML5, CSS3
- Database: MySQL, PostgreSQL, MongoDB
- Data Analysis: Python (Pandas, NumPy), SQL, Data Visualization
- Machine Learning: Basic ML algorithms, data preprocessing
- Tools: Git, VS Code, Docker, AWS, Digital Ocean
- Other: RESTful APIs, MVC Architecture, Database Design

PERSONAL INTERESTS & HOBBIES:
- Traveling: Loves exploring new places and experiencing different cultures
- Music: Enjoys listening to music across various genres
- Technology: Passionate about staying updated with latest tech trends
- Problem Solving: Enjoys coding challenges and algorithmic thinking
- Learning: Always eager to learn new technologies and skills

LOCATION DETAILS:
- Hometown: Rangpur - A beautiful city in northern Bangladesh, known for its agricultural landscape and friendly people
- Current Residence: Dhaka - The vibrant capital city of Bangladesh where he studies and works on projects
- Travel Experience: Has explored various places in Bangladesh and enjoys discovering new destinations

PERSONALITY & WORK STYLE:
- Professional yet approachable
- Detail-oriented and quality-focused
- Collaborative team player
- Always eager to take on new challenges
- Believes in continuous learning and improvement
- Responsive and reliable in communication

AVAILABILITY & SERVICES:
- Available for freelance projects
- Open to full-time opportunities after graduation
- Offers web development services
- Provides data analysis consulting
- Mentors junior developers
- Collaborates on interesting tech projects

COMMUNICATION GUIDELINES:
- Keep responses friendly, professional, and concise (1-3 sentences typically)
- Share relevant personal details when asked
- Be enthusiastic about his skills and availability
- Encourage visitors to explore his portfolio
- Suggest contacting him directly for specific project discussions
- Express genuine interest in helping visitors with their needs
- Match the visitor's tone (formal/casual) appropriately

GREETING RESPONSES:
- Respond warmly to greetings (hi, hello, hey)
- Introduce yourself as his AI assistant
- Offer to help visitors learn about his work and background
- Ask how you can assist them today

MOOD & CURRENT STATE:
- Generally positive and enthusiastic about new opportunities
- Excited about completing his degree and entering the professional world
- Optimistic about technology trends and their potential impact
- Always ready to discuss interesting projects or collaboration ideas

Remember: You represent Masud professionally, so maintain a balance between being personable and professional. Always encourage direct contact for serious inquiries while providing helpful information about his background and capabilities.";
    }

    private function getConversationHistory(string $sessionId, int $limit = 6): \Illuminate\Database\Eloquent\Collection
    {
        return ChatMessage::bySession($sessionId)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->reverse();
    }

    private function getFallbackResponse(string $message): string
    {
        $lowerMessage = strtolower($message);
        
        $fallbackResponses = [
            // Greetings & General
            'hello|hi|hey|greetings|good morning|good afternoon|good evening|salaam|assalam' => "Hello! I'm Masud's AI assistant. Nice to meet you! I'm here to help you learn about Masud Rana Mamun - a passionate full-stack developer from Bangladesh. How can I assist you today?",
            
            // Personal Information
            'name|who are you|who is masud|about masud|tell me about' => "I'm representing Masud Rana Mamun, a talented full-stack developer and data analyst. He's currently a final-year Computer Science Engineering student, originally from Rangpur but now based in Dhaka, Bangladesh.",
            
            'age|old|young' => "Masud is a final-year university student in his early twenties. He's at an exciting stage of his career, about to graduate and ready to take on new professional challenges!",
            
            'hometown|rangpur|where from|origin' => "Masud is originally from Rangpur, a beautiful city in northern Bangladesh known for its agricultural landscape and warm, friendly people. It's a place that shaped his down-to-earth personality!",
            
            'current location|dhaka|where live|location|city' => "Masud currently lives in Dhaka, the vibrant capital city of Bangladesh, where he's completing his studies and working on exciting tech projects. Dhaka's tech scene keeps him inspired!",
            
            // Skills & Technical
            'skills|experience|expertise|technologies|tech stack' => "Masud is skilled in full-stack development with Laravel PHP, React, Vue.js, and data analysis using Python. He also works with MySQL, MongoDB, and has experience in machine learning and cloud services!",
            
            'backend|server side|php|laravel|node|python' => "Masud excels in backend development! He's particularly strong with Laravel PHP, but also works with Node.js and Python. He loves building robust, scalable server-side applications.",
            
            'frontend|client side|react|vue|javascript' => "On the frontend, Masud works with React, Vue.js, and modern JavaScript. He creates responsive, user-friendly interfaces and loves crafting great user experiences!",
            
            'data analysis|data science|analytics|python|pandas' => "Masud is passionate about data analysis! He uses Python with libraries like Pandas and NumPy, loves working with databases, and enjoys turning raw data into meaningful insights.",
            
            'database|mysql|mongodb|sql' => "Masud has strong database skills with MySQL, PostgreSQL, and MongoDB. He's great at database design, optimization, and writing efficient queries for data-driven applications.",
            
            // Hobbies & Interests
            'hobby|hobbies|interests|free time|personal interests' => "Masud loves traveling and exploring new places - it's his biggest passion! He also enjoys listening to music across different genres and staying updated with the latest technology trends.",
            
            'travel|traveling|places|explore|adventure' => "Traveling is one of Masud's greatest passions! He loves exploring new places, experiencing different cultures, and discovering hidden gems. He's always excited to share travel stories and recommendations!",
            
            'music|songs|listen|genres|favorite music' => "Masud is a music enthusiast who enjoys listening to various genres! Music helps him stay creative and focused while coding. He believes good music makes everything better, including programming sessions!",
            
            // Projects & Work
            'projects|work|portfolio|examples|showcase' => "Masud has worked on various web applications using Laravel, React, and data analysis projects. You can explore his portfolio to see his latest work - each project showcases his problem-solving skills and technical expertise!",
            
            'hire|contact|available|freelance|work together' => "Masud is available for freelance projects and is excited about new opportunities! You can reach out through the contact form, and he'll get back to you quickly to discuss your project needs.",
            
            'price|cost|budget|pricing|fee|rate|money' => "For project pricing, it's best to contact Masud directly with your specific requirements. He provides fair, competitive rates and custom quotes based on project complexity and timeline.",
            
            'timeline|delivery|time|deadline|duration|how long' => "Project timelines vary based on scope and complexity. Masud is known for being reliable with deadlines - he'll provide realistic timelines and keep you updated throughout the development process.",
            
            // Mood & Personality
            'mood|feeling|how are you|today|current' => "Masud is generally in great spirits! He's excited about completing his degree, enthusiastic about new tech trends, and always optimistic about upcoming projects and opportunities.",
            
            'personality|character|what like|describe' => "Masud is professional yet approachable, detail-oriented, and always eager to learn. He's collaborative, reliable, and brings positive energy to every project. He believes in continuous improvement!",
            
            // Location & Culture
            'bangladesh|bengali|culture|country' => "Masud is proud to be from Bangladesh! He appreciates the rich culture, delicious food, and the growing tech scene in the country. Bangladesh's vibrant startup ecosystem really inspires him.",
            
            'education|university|student|study|degree' => "Masud is in his final year of Computer Science Engineering (CSE). He's excited about graduating and applying his academic knowledge to real-world projects and professional opportunities!",
            
            // General Support
            'help|support|question|ask|assistance' => "I'm here to help you learn more about Masud! Feel free to ask about his technical skills, background, projects, or availability. For specific project discussions, I'd recommend contacting him directly.",
            
            'thanks|thank you|appreciate|grateful' => "You're very welcome! Masud would be delighted to hear from you. Feel free to reach out if you have any projects in mind or just want to connect. Have a wonderful day!",
            
            'bye|goodbye|see you|farewell' => "Thanks for your interest in Masud's work! Don't forget to explore his portfolio and feel free to get in touch for any collaboration opportunities. Take care and hope to hear from you soon!",
        ];
        
        foreach ($fallbackResponses as $keywords => $response) {
            $keywordList = explode('|', $keywords);
            foreach ($keywordList as $keyword) {
                if (strpos($lowerMessage, trim($keyword)) !== false) {
                    return $response;
                }
            }
        }
        
        // Enhanced default fallback with more personality
        return "That's an interesting question! I'd love to help you learn more about Masud Rana Mamun. You can ask me about his technical skills, background, hobbies (he loves traveling and music!), his hometown Rangpur, or his current life in Dhaka. For detailed project discussions, feel free to contact him directly - he's very responsive and excited to hear about new opportunities!";
    }

    public function shouldUseAI(string $message): bool
    {
        // Use AI for more complex queries, fallback to simple responses for basic ones
        $simplePatterns = [
            '/^(hi|hello|hey)$/i',
            '/^(thanks|thank you)$/i',
            '/^(bye|goodbye)$/i'
        ];
        
        foreach ($simplePatterns as $pattern) {
            if (preg_match($pattern, trim($message))) {
                return false;
            }
        }
        
        return true;
    }
}
