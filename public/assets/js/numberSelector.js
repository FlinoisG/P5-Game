function numberSelector(target, maxNumber) {

    var numberSelectorMain = document.createElement('div');
    numberSelectorMain.id = "numberSelectorMain";
    numberSelectorMain.className = "numberSelectorMain";

        var numberSelector = document.createElement('div');
        numberSelector.id = "numberSelector";
        numberSelector.className = "numberSelector";

            var numberSelectorLeft = document.createElement('div');
            numberSelectorLeft.id = "numberSelectorLeft";
            numberSelectorLeft.className = "numberSelectorElem numberSelectorHandle numberSelectorLeft";
            numberSelectorLeft.textContent = "<";

            var numberSelectorNumber = document.createElement('div');
            numberSelectorNumber.id = "numberSelectorNumber";
            numberSelectorNumber.className = "numberSelectorElem numberSelectorNumber";
            numberSelectorNumber.textContent = "1";

            var numberSelectorRight = document.createElement('div');
            numberSelectorRight.id = "numberSelectorRight";
            numberSelectorRight.className = "numberSelectorElem numberSelectorHandle numberSelectorRight";
            numberSelectorRight.textContent = ">";

        var numberSelectorText = document.createElement('span');
        numberSelectorText.id = "numberSelectorText";
        numberSelectorText.className = "numberSelectorText";
        numberSelectorText.textContent = "sur ";

        var numberSelectorTotalNum = document.createElement('span');
        numberSelectorTotalNum.id = "numberSelectorTotalNum";
        numberSelectorTotalNum.className = "numberSelectorTotalNum";
        numberSelectorTotalNum.textContent = maxNumber;

    numberSelectorLeft.addEventListener('click', function(){
        var number = Number(numberSelectorNumber.innerText);
        if (number > 0){
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

    numberSelector.appendChild(numberSelectorLeft);
    numberSelector.appendChild(numberSelectorNumber);
    numberSelector.appendChild(numberSelectorRight);

    numberSelectorMain.appendChild(numberSelector);
    numberSelectorMain.appendChild(numberSelectorText);
    numberSelectorMain.appendChild(numberSelectorTotalNum);

    target.appendChild(numberSelectorMain);

}