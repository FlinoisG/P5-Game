function numberSelector(target, maxNumber, type) {

    if (type == "move"){
        title = "Déplacer";
        selectorNumber = "numberSelectorMoveNumber";
    } else if (type == "attack"){
        title = "Attaquer";
        selectorNumber = "numberSelectorAttackNumber";
    }

    var numberSelectorMain = document.createElement('div');
    numberSelectorMain.id = "numberSelectorMain";
    numberSelectorMain.className = "numberSelectorMain";

        var numberSelectorTitle = document.createElement('div');
        numberSelectorTitle.id = "numberSelectorTitle";
        numberSelectorTitle.className = "numberSelectorTitle";
        numberSelectorTitle.textContent = title;

        var numberSelector = document.createElement('div');
        numberSelector.id = "numberSelector";
        numberSelector.className = "numberSelector";

            var numberSelectorExtraLeft = document.createElement('div');
            numberSelectorExtraLeft.id = "numberSelectorExtraLeft";
            numberSelectorExtraLeft.className = "numberSelectorElem numberSelectorHandle numberSelectorExtraLeft";
            numberSelectorExtraLeft.textContent = "<<";

            var numberSelectorLeft = document.createElement('div');
            numberSelectorLeft.id = "numberSelectorLeft";
            numberSelectorLeft.className = "numberSelectorElem numberSelectorHandle numberSelectorLeft";
            numberSelectorLeft.textContent = "<";

            var numberSelectorNumber = document.createElement('div');
            numberSelectorNumber.id = selectorNumber;
            numberSelectorNumber.className = "numberSelectorElem numberSelectorNumber";
            numberSelectorNumber.textContent = "1";

            var numberSelectorRight = document.createElement('div');
            numberSelectorRight.id = "numberSelectorRight";
            numberSelectorRight.className = "numberSelectorElem numberSelectorHandle numberSelectorRight";
            numberSelectorRight.textContent = ">";

            var numberSelectorExtraRight = document.createElement('div');
            numberSelectorExtraRight.id = "numberSelectorExtraRight";
            numberSelectorExtraRight.className = "numberSelectorElem numberSelectorHandle numberSelectorExtraRight";
            numberSelectorExtraRight.textContent = ">>";

        var numberSelectorText = document.createElement('span');
        numberSelectorText.id = "numberSelectorText";
        numberSelectorText.className = "numberSelectorText";
        numberSelectorText.textContent = "sur ";

        var numberSelectorTotalNum = document.createElement('span');
        numberSelectorTotalNum.id = "numberSelectorTotalNum";
        numberSelectorTotalNum.className = "numberSelectorTotalNum";
        numberSelectorTotalNum.textContent = maxNumber;

    numberSelectorExtraLeft.addEventListener('click', function(){
        var number = Number(numberSelectorNumber.innerText);
        if (number > 5){
            number = number - 5;
            numberSelectorNumber.innerText = number;
        } else {
            numberSelectorNumber.innerText = 1;
        }
    });

    numberSelectorLeft.addEventListener('click', function(){
        var number = Number(numberSelectorNumber.innerText);
        if (number > 1){
            number--;
            numberSelectorNumber.innerText = number;
        }
    });

    numberSelectorRight.addEventListener('click', function(){
        var number = Number(numberSelectorNumber.innerText);
        if (number < maxNumber){
            number++;
            numberSelectorNumber.innerText = number;
        }
    });

    numberSelectorExtraRight.addEventListener('click', function(){
        var number = Number(numberSelectorNumber.innerText);
        if (number < (maxNumber - 4)){
            number = number + 5;
            numberSelectorNumber.innerText = number;
        } else {
            numberSelectorNumber.innerText = maxNumber;
        }
    });

    numberSelector.appendChild(numberSelectorExtraLeft);
    numberSelector.appendChild(numberSelectorLeft);
    numberSelector.appendChild(numberSelectorNumber);
    numberSelector.appendChild(numberSelectorRight);
    numberSelector.appendChild(numberSelectorExtraRight);

    numberSelectorMain.appendChild(numberSelectorTitle);
    numberSelectorMain.appendChild(numberSelector);
    numberSelectorMain.appendChild(numberSelectorText);
    numberSelectorMain.appendChild(numberSelectorTotalNum);

    target.appendChild(numberSelectorMain);

}