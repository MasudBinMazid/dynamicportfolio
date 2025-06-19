nav = document.querySelector('.navbar').children;
for (i = 0; i < nav.length; i++) {
  nav[i].addEventListener('click', function (event) {
    console.log(event.target.innerText);
  });
}


btn = document.getElementById('change');
btn.addEventListener('click', function () {
    username = document.getElementById('username');
    username.innerText = 'Masud Bin Mazid';
    username.style.color = 'red';
    username.style.fontSize = '2rem';
    username.style.fontWeight = 'bold';
});

