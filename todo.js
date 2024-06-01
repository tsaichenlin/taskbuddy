document.addEventListener("DOMContentLoaded", function () {
  (function loadData() {
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
      if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
        if (xhr.responseText.includes("Error")) {
          console.log("No Task");
        } else {
          taskList = xhr.responseText.match(/\[(.*?)\]/g);
          taskList.forEach((newTask) => {
            newTask = JSON.parse(newTask);
            addTaskToSpecific(newTask[0], newTask[1], parseInt(newTask[2]));
          });
        }
      } else {
        console.log("Server Error: " + xhr.responseText);
      }
    };
    xhr.open("POST", "loadData.php", true);
    xhr.send();
  })();

  let taskButton = document.getElementById("add-button");
  let taskPopup = document.getElementById("addtask-popup");

  taskButton.addEventListener("click", () => {
    taskPopup.style.display = "block";
  });

  document.getElementById("close-popup").addEventListener("click", () => {
    taskPopup.style.display = "none";
  });

  document.getElementById("newtask-add").addEventListener("click", () => {
    let taskName = document.getElementById("newtask-name").value;
    let dueDate = document.getElementById("newtask-due").value;
    if (taskName && dueDate) {
      addTask(taskName, dueDate);
      const formData = new FormData();
      formData.append("taskName", taskName);
      formData.append("dueDate", dueDate);

      document.getElementById("newtask-name").value = "";
      document.getElementById("newtask-due").value = "";
      taskPopup.style.display = "none";
      //data update

      var xhr = new XMLHttpRequest();
      xhr.onreadystatechange = function () {
        console.log("change");
        if (xhr.readyState === XMLHttpRequest.DONE) {
          if (xhr.status === 200) {
            if (xhr.responseText.includes("Task Added")) {
              console.log("Task Added");
            }
          } else {
            console.log("Error" + xhr.responseText);
          }
        }
      };

      xhr.open("POST", "addTask.php", true);
      xhr.send(formData);
    } else {
      alert("Please fill in all fields.");
    }
  });
});

function addTask(taskName, dueDate) {
  let newTaskDiv = createTaskElement(taskName, dueDate);
  let currentList = document.getElementById("todo-list");
  currentList.appendChild(newTaskDiv);
}
function addTaskToSpecific(taskName, dueDate, location) {
  let newTaskDiv = createTaskElement(taskName, dueDate);
  let currentList;
  if (location == 0) {
    currentList = document.getElementById("todo-list");
  } else if (location == 1) {
    currentList = document.getElementById("inprogress-list");
    let leftArrow = newTaskDiv.querySelector(".move-left");
    if (leftArrow) {
      leftArrow.style.display = "inline";
    }
  } else if (location == 2) {
    newTaskDiv.classList.add("completed-task");
    currentList = document.getElementById("completed-list");
    let leftArrow = newTaskDiv.querySelector(".move-left");
    if (leftArrow) {
      leftArrow.style.display = "inline";
    }
  } else {
    return;
  }
  currentList.appendChild(newTaskDiv);
}

function createTaskElement(taskName, dueDate) {
  let newTaskDiv = document.createElement("article");
  let newName = document.createElement("p");
  newName.className = "task-name";
  newName.textContent = taskName;
  let newDueDate = document.createElement("p");
  newDueDate.className = "due-date";
  newDueDate.innerText = "Due " + dueDate;

  let moveLeftSpan = createMoveSpan("←", "move-left", "left");
  moveLeftSpan.style.display = "none";

  let moveRightSpan = createMoveSpan("→", "move-right", "right");

  newTaskDiv.appendChild(newName);
  newTaskDiv.appendChild(newDueDate);
  newTaskDiv.appendChild(moveLeftSpan);
  newTaskDiv.appendChild(moveRightSpan);

  return newTaskDiv;
}

function createMoveSpan(text, className, direction) {
  let moveSpan = document.createElement("span");
  moveSpan.textContent = text;
  moveSpan.className = `move-arrow ${className}`;
  moveSpan.addEventListener("click", function (event) {
    event.stopPropagation();
    moveTask(this.parentNode, direction);
  });
  return moveSpan;
}

function moveTask(taskElement, direction) {
  let currentListId = taskElement.parentNode.id;
  let targetParentId;
  let move;

  if (currentListId === "todo-list") {
    move = 0;
  }

  if (direction === "right") {
    if (currentListId === "todo-list") {
      targetParentId = "inprogress-list";
      move = 1;
    } else if (currentListId === "inprogress-list") {
      targetParentId = "completed-list";
      move = 2;
    }
  } else if (direction === "left") {
    if (currentListId === "inprogress-list") {
      targetParentId = "todo-list";
      move = 0;
    } else if (currentListId === "completed-list") {
      targetParentId = "inprogress-list";
      move = 1;
    }
  }

  if (targetParentId) {
    document.getElementById(targetParentId).appendChild(taskElement);
    console.log(`Task moved from ${currentListId} to ${targetParentId}`);
  }

  if (targetParentId === "completed-list") {
    taskElement.classList.add("completed-task");
  } else {
    taskElement.classList.remove("completed-task");
  }
  let leftArrow = taskElement.querySelector(".move-left");
  let rightArrow = taskElement.querySelector(".move-right");

  if (leftArrow) {
    leftArrow.style.display =
      targetParentId === "todo-list" ? "none" : "inline";
  }

  if (rightArrow) {
    rightArrow.style.display =
      targetParentId === "completed-list" ? "none" : "inline";
  }

  var xhr = new XMLHttpRequest();
  xhr.onreadystatechange = function () {
    if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
      if (xhr.responseText.includes("Task Updated")) {
        console.log("Task Updated");
      } else {
        console.log("Unable to Move Task");
      }
    } else {
      console.log("Server Error: " + xhr.responseText);
    }
  };
  const name = taskElement.querySelector(".task-name");

  formData = new FormData();
  formData.append("progress", move);
  formData.append("taskName", name.innerHTML);
  xhr.open("POST", "changeProgress.php", true);
  xhr.send(formData);
}

document
  .getElementById("delete-completed-tasks")
  .addEventListener("click", function (event) {
    event.preventDefault();
    const completedList = document.getElementById("completed-list");
    while (completedList.firstChild) {
      completedList.removeChild(completedList.firstChild);
    }
    const header = document.createElement("h1");
    header.textContent = "Completed";
    completedList.appendChild(header);
  });
