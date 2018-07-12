var newUserPanel = document.createElement('div');
newUserPanel.id = 'newUserPanel';
newUserPanel.className = "newUserPanel";

var newUserTitle = document.createElement('h2');
newUserTitle.id = 'newUserTitle';
newUserTitle.className = 'newUserTitle';
newUserTitle.textContent = 'Bienvenu !';

var newUserText = document.createElement('p');
newUserText.id = 'newUserText';
newUserText.className = 'newUserText';
newUserText.textContent = 'Construisez votre première base en cliquant sur l\'icone sous ce texte puis sur un emplacement sur la carte. Si c\'est votre première partie, il est conseillé de construire votre première base loins des autres et près d\'un gisement de metal.';

var newUserText2 = document.createElement('p');
newUserText2.id = 'newUserText2';
newUserText2.className = 'newUserText';
newUserText2.textContent = 'Comme c\'est votre première base, elle est gratuite et se construit instantanément.'

var newUserSubPanel = document.createElement('div');
newUserSubPanel.id = 'newUserSubPanel';
newUserSubPanel.className = 'newUserSubPanel panelSubOption';

var newUserInner = document.createElement('div');
newUserInner.id = 'newUserInner';
newUserInner.className = 'newUserInner optionInner';

var newUserPanelIcon = document.createElement('img');
newUserPanelIcon.id = 'newUserPanelIcon';
newUserPanelIcon.className = 'newUserPanelIcon panelSubIcon';
newUserPanelIcon.src = "../public/assets/img/unit_slot_base.png";

newUserPanelIcon.addEventListener('click', function(){
    build.build('base', 'none,none');
});

var newUserPanelText = document.createElement('span');
newUserPanelText.id = 'newUserPanelText';
newUserPanelText.className = 'newUserPanelText panelSubText';
newUserPanelText.innerHTML = 'Acheter base'; 

newUserInner.appendChild(newUserPanelIcon);
newUserInner.appendChild(newUserPanelText);
newUserSubPanel.appendChild(newUserInner);

newUserPanel.appendChild(newUserTitle);
newUserPanel.appendChild(newUserText);
newUserPanel.appendChild(newUserText2);
newUserPanel.appendChild(newUserSubPanel);

document.getElementById('panel').appendChild(newUserPanel);