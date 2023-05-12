var myDiv = document.getElementById('main'),
    myP = document.createElement('p'),
    myText = document.createTextNode('Nour From javascript'),
    myButton = document.createElement('button'),
    myText2 = document.createTextNode('Click Here');


    myDiv.appendChild(myButton);
    myP.appendChild(myText);
    myDiv.appendChild(myP);
    myButton.appendChild(myText2);

    document.querySelector("p").style.color = "#FFF";
    document.querySelector("button").style.width = "50px";
    document.querySelector("button").style.height = "50px";
    

    myButton.onclick = function() {
        'use strict';

        this.parentElement.style.display = "none";
    }

    // window.onload = function() {
    //     'use strict';

    //     setTimeout( function() {
    //         myButton.click()
    //     }, 3000);
    // }



    var myBox1 = document.getElementById('box1'),
        myButtons = document.querySelector('.myButton');

        myButtons.onclick = function() {
            'use strict';

            myBox1.classList.toggle('hidden')
        }

console.log(myDiv);
console.log(myButton);
console.log(myP);