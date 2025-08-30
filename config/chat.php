<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Auto Response Settings
    |--------------------------------------------------------------------------
    | Configure automatic responses for the live chat system
    */
    
    'auto_response_enabled' => env('CHAT_AUTO_RESPONSE', true),
    
    'welcome_message' => "Thank you for reaching out! I've received your message and will get back to you as soon as possible. In the meantime, feel free to browse my portfolio to see more of my work.",
    
    'keyword_responses' => [
        'price|cost|budget|pricing|fee|rate' => "I'd be happy to discuss pricing with you! Project costs vary depending on scope and requirements. Let me know more details about your project and I'll provide you with a detailed quote.",
        
        'timeline|delivery|time|deadline|duration|how long' => "Project timelines depend on complexity and scope. Typically, small projects take 1-2 weeks, while larger projects may take 4-8 weeks. I'll provide a detailed timeline once I understand your requirements better.",
        
        'experience|portfolio|work|skills|expertise' => "I have extensive experience in web development with technologies like Laravel, React, Vue.js, and more. Feel free to check out my portfolio projects to see examples of my work!",
        
        'contact|email|phone|reach|call' => "You can reach me through this chat, email, or the contact form on my website. I typically respond within a few hours during business days.",
        
        'available|availability|free|busy' => "I'm currently accepting new projects! My availability depends on project scope and timeline. Let's discuss your requirements and I'll let you know my current schedule.",
        
        'technology|tech|stack|framework|language' => "I work with a variety of technologies including PHP (Laravel), JavaScript (React, Vue.js, Node.js), Python, databases (MySQL, PostgreSQL), and cloud services (AWS, Digital Ocean). What technology are you interested in?",
        
        'help|support|question|ask' => "I'm here to help! Feel free to ask me anything about web development, my services, or specific projects you have in mind.",
    ],
    
    'email_notifications' => env('CHAT_EMAIL_NOTIFICATIONS', true),
    
    'response_delay' => [
        'min' => 2, // minimum seconds before auto-response
        'max' => 5, // maximum seconds before auto-response
    ],

    /*
    |--------------------------------------------------------------------------
    | AI Integration Settings
    |--------------------------------------------------------------------------
    | Configure AI-powered responses for the chat system
    */
    
    'ai_enabled' => env('CHAT_AI_ENABLED', false),
    
    'ai_model' => env('CHAT_AI_MODEL', 'gpt-3.5-turbo'),
    
    'ai_max_tokens' => env('CHAT_AI_MAX_TOKENS', 150),
    
    'ai_fallback_to_keywords' => env('CHAT_AI_FALLBACK', true),
    
    'ai_response_delay' => [
        'min' => 3, // minimum seconds before AI response
        'max' => 6, // maximum seconds before AI response
    ],

    'ai_system_context' => [
        'name' => 'Masud Rana Mamun',
        'role' => 'Full-Stack Developer & Data Analyst',
        'education' => 'Final-year Computer Science Engineering student',
        'skills' => ['Laravel PHP', 'React', 'Vue.js', 'Python', 'Data Analysis', 'Machine Learning'],
        'availability' => 'Available for freelance projects and collaboration'
    ],
];
