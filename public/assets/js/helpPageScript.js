var helpPageTitle = document.getElementById("helpPageTitle");
var helpPageContent = document.getElementById("helpPageContent");

var helpMenu1 = document.getElementById("helpMenu1");
var helpMenu2 = document.getElementById("helpMenu2");
var helpMenu3 = document.getElementById("helpMenu3");

var title1 = document.getElementById("title1");
var content1 = document.getElementById("content1");

var title2 = document.getElementById("title2");
var content2 = document.getElementById("content2");

var title3 = document.getElementById("title3");
var content3 = document.getElementById("content3");

helpPageTitle.textContent = title1.textContent;
helpPageContent.textContent = content1.textContent;

helpMenu1.addEventListener('click', function(){
    helpPageTitle.textContent = title1.textContent;
    helpPageContent.innerHTML = content1.innerHTML;
});

helpMenu2.addEventListener('click', function(){
    helpPageTitle.textContent = title2.textContent;
    helpPageContent.innerHTML = content2.innerHTML;
});

helpMenu3.addEventListener('click', function(){
    helpPageTitle.textContent = title3.textContent;
    helpPageContent.innerHTML = content3.innerHTML;
});