// Change the background color of the page
function changeColor() {
    let color = document.getElementById('colorInputColor').value;
    document.body.style.backgroundColor = color;
    // document.getElementById('colorInputText').value = color;
  }

// Change the text and save it 
const editBtn = document.getElementById('editBtn');
const editables = document.querySelectorAll('#title, #content')
editBtn.addEventListener('click', function(e) {
  if (!editables[0].isContentEditable) {
    editables[0].contentEditable = 'true';
    editables[1].contentEditable = 'true';
    editBtn.innerHTML = 'Save Changes';
    editBtn.style.backgroundColor = '#6F9';
  } else {
    // Disable Editing 
    editables[0].contentEditable = 'false';
    editables[1].contentEditable = 'false';
    // Change Button Text and Color 
    editBtn.innerHTML = 'Enable Editing';
    editBtn.style.backgroundColor = '#F96';
    // Save the data in localStorage 
    for (var i = 0; i < editables.length; i++) {
      localStorage.setItem(editables[i].getAttribute('id'), editables[i].innerHTML);
    }
  }
});

// To save the text if you refrech the page
if (typeof(Storage) !== "undefined") {
  if (localStorage.getItem('title') !== null) {
    editables[0].innerHTML = localStorage.getItem('title');
  }
  if (localStorage.getItem('content') !== null) {
    editables[1].innerHTML = localStorage.getItem('content');
  } 
}

// Auto save in the localStorage
document.addEventListener('keydown', function(e) {
  for (var i = 0; i < editables.length; i++) {
    localStorage.setItem(editables[i].getAttribute('id'), editables[i].innerHTML);
  }
});

// To add H1
document.querySelector("#addh1").addEventListener("click", (e) => {
  const text = prompt(
      "What text do you want the heading to have?",
      "Heading"
  );
  editables[0].innerHTML = editables[0].innerHTML + `<h1>${text}</h1>`;
});

document.querySelector("#removeh1").addEventListener("click", (e) => {
  const h1 = document.querySelector("h1");
  if (h1) {
    h1.remove();
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

// Sidepar tools
tinymce.init({
  selector: '#mytextarea',
  height: 200,
  plugins: [
    'a11ychecker','advlist','advcode','advtable','autolink','checklist','export',
    'lists','link','image','charmap','preview','anchor','searchreplace','visualblocks',
    'powerpaste','fullscreen','formatpainter','insertdatetime','media','table','help','wordcount'
  ],
  toolbar: 'undo redo | formatpainter casechange blocks | bold italic backcolor | ' +
    'alignleft aligncenter alignright alignjustify | ' +
    'bullist numlist checklist outdent indent | removeformat | a11ycheck code table help'
});

// $(function() {
//   $("#panelsStayOpen-collapseOne").resizable({
//     handles: "e, w", // only allow resizing from the left and right sides
//     minWidth: 200, // set a minimum width of 200 pixels
//     maxWidth: 800 // set a maximum width of 800 pixels
//   });
// });