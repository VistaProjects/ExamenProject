// Sidebar controlpanel
var mySection = document.getElementById('hero'),
  sideButton = document.createElement('div');
sideButton.innerHTML = `
<div id="delete">
  <!-- create button to call saveHtml() -->
  <button onclick="saveHtml()">Save</button>

  <!-- Button trigger offcanvas -->
  <a class="btn btn-primary" data-bs-toggle="offcanvas" href="#offcanvasExample" role="button" aria-controls="offcanvasExample">
  Control sidebar
  </a>



  <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel" data-bs-scroll="true" data-bs-backdrop="false">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title" id="offcanvasExampleLabel">Control Panel</h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body">
    <!-- show accrodion -->
    <div class="accordion" id="accordionPanelsStayOpenExample">

      <!-- Start Editing The Text Nour -->
      <div class="accordion-item">
        <h2 class="accordion-header" id="panelsStayOpen-headingFive">
          <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseFive" aria-expanded="true" aria-controls="panelsStayOpen-collapseFive">
            <h6>Edit Text</h6>
          </button>
        </h2>
        <div id="panelsStayOpen-collapseFive" class="accordion-collapse collapse show" aria-labelledby="panelsStayOpen-headingOne">
          <div class="accordion-body">
            <div class="mb-3">
              <label for="formGroupExampleInput" class="form-label">Change the Text</label>
              
              <!-- <input type="text" class="form-control" id="formGroupExampleInput" placeholder="Example input placeholder"> -->
              <textarea type="text" class="form-control" id="mytextarea" placeholder="Example input placeholder" value=""></textarea>
            </div>
            <div class="mb-3">
              <label for="formGroupExampleInput2" class="form-label">Change color</label>
              <input type="text" class="form-control" id="formGroupExampleInput2" placeholder="Another input placeholder">
            </div>
          </div>
        </div>
      </div>
      <!-- End Editing The Text Nour -->

      <!-- Start Editing The Text Nour -->
      
      <!-- End Editing The Text Nour-->


      <!-- Start Editing The Photos-->
      <div class="accordion-item">
        <h2 class="accordion-header" id="panelsStayOpen-headingTwo">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseTwo" aria-expanded="false" aria-controls="panelsStayOpen-collapseTwo">
            <h6>Edit photos</h6>
          </button>
        </h2>
        <div id="panelsStayOpen-collapseTwo" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-headingTwo">
          <div class="accordion-body">
            <strong>This is the second item's accordion body.</strong> It is hidden by default, until the collapse plugin adds the appropriate classes that we use to style each element. These classes control the overall appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go within the <code>.accordion-body</code>, though the transition does limit overflow.
          </div>
        </div>
      </div>
      <!-- End Editing The Photos -->

      <!-- Start Editing The Videos-->
      <div class="accordion-item">
        <h2 class="accordion-header" id="panelsStayOpen-headingThree">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseThree" aria-expanded="false" aria-controls="panelsStayOpen-collapseThree">
            <h6>Edit videos</h6>
          </button>
        </h2>
        <div id="panelsStayOpen-collapseThree" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-headingThree">
          <div class="accordion-body">
            <strong>This is the third item's accordion body.</strong> It is hidden by default, until the collapse plugin adds the appropriate classes that we use to style each element. These classes control the overall appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go within the <code>.accordion-body</code>, though the transition does limit overflow.
          </div>
        </div>
      </div>
      <!-- End Editing The Videos-->



    </div>
  </div>
  </div>
</div>
`;


function saveHtml() {
  // remove all the divs with id delete
  $('*[id=delete]').remove();
  
  let html = document.documentElement.outerHTML
  
  // Create a Blob object from the string data
  var blob = new Blob([html], { type: 'text/plain' });

  // Create a FormData object
  var formData = new FormData();
  formData.append('file', blob, 'new.html');

  // Create a new XMLHttpRequest object
  var xhr = new XMLHttpRequest();

  // Set up the AJAX request
  xhr.open('POST', '../backend/?Update=true', true);

  // Define the callback function when the request is complete
  xhr.onload = function() {
    if (xhr.status === 200) {
      console.log(xhr.responseText);
      console.log('File uploaded successfully');
    } else {
      console.log('Error uploading file');
    }
  };

  // Send the FormData object to the PHP server
  xhr.send(formData);

}


function capitalize(string) {
  return string.charAt(0).toUpperCase() + string.slice(1);
}

function loadData() {
  fetch('http://localhost/ExamenProject/backend/?item=all')
  .then(response => response.json())
  .then(data => {
    // this is the example data : {"cards":{"test":"<p>hoi rik<\/p>","tete":"ewtwetw","tetwe":"dsggsg","dsgsdgsd":"dsgdsg","testingmetrik":"<p>hoi rik<\/p> <html>"},"navbar":{"Testing":"<!DOCTYPE html> <html> <head> \t<title>HTML Form Example<\/title> <\/head> <body>  <h2>HTML Form Example<\/h2>","rikiee":"<p>hoi rik<\/p> <html>"},"success":true}
    console.log(data);
    // if (data.success == false) alert(data.error.text);
  
    var accordion = document.querySelector('.accordion')
    console.log(accordion);
    // loop through the properties of the object and use that as the title and
    // content of the accordion
    for (const [key, value] of Object.entries(data)) {
      // value.forEach(property => {
      //   console.log(property);
      // });

      var newKey = "_" + key
      
      if (key == 'success') continue
      
      
      // Create a new accordion item for each key
      var html= `
      <div class="accordion-item">
        <h2 class="accordion-header" id="panelsStayOpen-headingThree">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapse${newKey}" aria-expanded="false" aria-controls="panelsStayOpen-collapse${newKey}">
            <h6>${capitalize(key)}</h6>
          </button>
        </h2>
        <div id="panelsStayOpen-collapse${newKey}" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-heading${newKey}">
        <div id="${newKey}" class="accordion-body">
        </div>
        </div>
      </div>`;
      
      let div = document.createElement('div');
      div.innerHTML = html;
      accordion.appendChild(div);


      // Create a inner block for each property
      for (let title in value) {
        if (value.hasOwnProperty(title)) {
          console.log(title + ": " + value[title]);
          let mainDiv = document.getElementById(newKey);
          let div = document.createElement('div');
          div.setAttribute('class', 'drag');
          div.setAttribute('draggable', 'true');
          // div.setAttribute('id', 'borderTest');
          div.innerHTML = value[title];
          
          mainDiv.innerHTML += `<h4>${capitalize(title)}</h4>` 
          mainDiv.appendChild(div);
          mainDiv.innerHTML += '<hr>'
          
        }
      }
    }
  });
}




// styleing sidebar
sideButton.style.zIndex = 10;
sideButton.style.position = "fixed";
mySection.appendChild(sideButton);
mySection.insertBefore(sideButton, mySection.firstElementChild);


const myDivSideBar = document.getElementsByClassName('offcanvas offcanvas-start')[0];
myDivSideBar.style.transform = 'translateY(89px)';

var checkExist = setInterval(function() {
  if (document.querySelectorAll('.accordion').length) {
     console.log("Adding data!");
     loadData();
     clearInterval(checkExist);
  }
}, 100); // check every 100ms


// get the sidebar button
var sideButton = document.querySelector('.btn-primary');

// add an event listener to the button to open the sidebar
sideButton.addEventListener('click', () => {
  const offcanvas = new bootstrap.Offcanvas(document.getElementById('offcanvasExample'));
  offcanvas.show();
});

// get all the paragraphs and headers on the page
const elements = document.querySelectorAll('p, h1, h2, h3, h4, h5, h6');

// add an event listener to each element
elements.forEach(element => {
  element.addEventListener('click', () => {
    // add a class to the clicked element
    element.classList.add('clicked');
    // remove the class from all the other elements
    const allElements = document.querySelectorAll('.clicked');
    allElements.forEach(element => {
      element.classList.remove('clicked');
      });
      const text = element.cloneNode(true).textContent;

    // add the clicked text to the modal
    // put the text in the right place
    var iframe = document.getElementById("mytextarea_ifr");
    var elmnt = iframe.contentWindow.document.getElementById("tinymce");
    elmnt.removeAttribute('data-mce-placeholder');
    elmnt.removeAttribute('aria-placeholder');



    // const classDiv = document.getElementById('tinymce');
    const p = iframe.contentWindow.document.getElementsByTagName("p")[0];
    p.innerHTML = text;
    // elmnt.appendChild(p);
    // const para = document.getElementById("tinymce").lastElementChild;
    console.log(p);
    // classDiv.appendChild(sidebarTextArea);
    // classDiv.insertBefore(sidebarTextArea, classDiv.firstElementChild);


    // sidebarTextArea.nodeTextContent;
    
    // show the sidebar
    const offcanvas = new bootstrap.Offcanvas(document.getElementById('offcanvasExample'));
    offcanvas.show();

    // initialize TinyMCE on the text area
    // tinymce.init({
    //   selector: '#mytextarea',
    //   height: 300,
    //   plugins: [
    //     'a11ychecker','advlist','advcode','advtable','autolink','checklist','export',
    //     'lists','link','image','charmap','preview','anchor','searchreplace','visualblocks',
    //     'powerpaste','fullscreen','formatpainter','insertdatetime','media','table','help','wordcount'
    //   ],
    //   toolbar: 'undo redo | formatpainter casechange blocks | bold italic backcolor | ' +
    //     'alignleft aligncenter alignright alignjustify | ' +
    //     'bullist numlist checklist outdent indent | removeformat | a11ycheck code table help',
    //   setup: editor => {
    //     // listen for changes in the editor content and update the original element
    //     editor.on('change', () => {
    //       const newContent = editor.getContent();
    //       element.textContent = newContent;
    //     });
    //   }
    // });
  });
});



// // get all the paragraphs and headers on the page
// const elements = document.querySelectorAll('p, h1, h2, h3, h4, h5, h6');
// // console.log(elements);

// // add an event listener to each element
// elements.forEach(element => {
//   element.addEventListener('click', () => {
//     const text = element.textContent; // get the text content of the clicked element
// console.log(text);
//     // open the sidebar and populate it with the text content
//     // you can use the same code we used earlier to create the sidebar and populate it with content
//     // here, we'll assume you've stored the sidebar element in a variable called "sidebar"
//     // const sidebarBody = sidebar.querySelector('.offcanvas-body');
//     const sidebar = document.querySelector('#offcanvasExample');
//     const sidebarBody = sidebar.classList.toggle('show');
//     sidebarBody.innerHTML = `<textarea id="editor">${text}</textarea>`;
//     console.log(sidebarBody);

//         // initialize TinyMCE on the text area
//     tinymce.init({
//       selector: '#editor',
//       height: 200,
//       plugins: [
//         'a11ychecker','advlist','advcode','advtable','autolink','checklist','export',
//         'lists','link','image','charmap','preview','anchor','searchreplace','visualblocks',
//         'powerpaste','fullscreen','formatpainter','insertdatetime','media','table','help','wordcount'
//       ],
//       toolbar: 'undo redo | formatpainter casechange blocks | bold italic backcolor | ' +
//         'alignleft aligncenter alignright alignjustify | ' +
//         'bullist numlist checklist outdent indent | removeformat | a11ycheck code table help',
//       setup: editor => {
//         // listen for changes in the editor content and update the original element
//         editor.on('change', () => {
//           const newContent = editor.getContent();
//           element.textContent = newContent;
//         });
//       }
//     });

//     // show the sidebar
//     const offcanvas = new bootstrap.Offcanvas(sidebar);
//     offcanvas.show();
//   });
// });


// // To have border around the Element on hover
// window.addEventListener("mouseover", (e) => {

//   if (!e.target) return
  
//   let element = e.target

//   element.style.border = "1px solid red";

//   // add border to the element that is hovered
//   // let element = document.getElementById(`${e.target.id}`);

//   // element.style.border = "1px solid red";
// });

// window.addEventListener("mouseout", (e) => {
//   if (!e.target) return
  
//   let element = e.target

//   element.style.border = "0px solid red";

// });

// To make it active and working
tinymce.init({
  selector: '#mytextarea',
  height: 300,
  plugins: [
    'a11ychecker','advlist','advcode','advtable','autolink','checklist','export',
    'lists','link','image','charmap','preview','anchor','searchreplace','visualblocks',
    'powerpaste','fullscreen','formatpainter','insertdatetime','media','table','help','wordcount'
  ],
  toolbar: 'undo redo | formatpainter casechange blocks | bold italic backcolor | ' +
    'alignleft aligncenter alignright alignjustify | ' +
    'bullist numlist checklist outdent indent | removeformat | a11ycheck code table help',
  setup: editor => {
    // listen for changes in the editor content and update the original element
    editor.on('change', () => {
      const newContent = editor.getContent();
      element.textContent = newContent;
    });
  }
});


// To have border around the Element on hover
window.addEventListener("mouseover", (e) => {

  if (!e.target) return
  
  let element = e.target

  element.style.border = "1px solid red";

  // add border to the element that is hovered
  // let element = document.getElementById(`${e.target.id}`);

  // element.style.border = "1px solid red";
});

window.addEventListener("mouseout", (e) => {
  if (!e.target) return
  
  let element = e.target

  element.style.border = "0px solid red";

  // add border to the element that is hovered
  // let element = document.getElementById(`${e.target.id}`);

  // element.style.border = "0px solid red";
});

