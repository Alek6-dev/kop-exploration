// @ts-nocheck
//
// Todo
// --------------------------------------------------------------------------

// --------------------------------------------------------------------------

// For use todo
// Add attribute data-todo="some description" on html tag
// ex: <a href="#" class="link" data-todo="In progress, we need to replace href with correct value. Wait Back/front adjust">Click</a>
// In app you can show/hide elements with radio on bottom left of page
// --------------------------------------------------------------------------

function todo() {
  const todos = document.querySelectorAll('[data-todo]');

  function addStyles() {
    const head = document.getElementsByTagName('head')[0];

    const styleTag = document.createElement('style');
    const styles = `
          .todo-toggle {
            position: fixed;
            bottom: 36px;
            left: 0;
            z-index: 1000;
            padding: 7px 10px 8px 60px;
            color: #fff;
            font-size: 14px;
            background: #333;
            border: 0;
            border-radius: 0 4px 4px 0;
            cursor: pointer;
          }

          .todo-toggle::before,
          .todo-toggle::after {
            position: absolute;
            top: 50%;
            left: 10px;
            transform: translate(0, -50%);
            content: "";
          }

          .todo-toggle::before {
            width: 40px;
            height: 20px;
            background: #ccc;
            border: 1px solid #fff;
            border-radius: 10px;
            transition: background-color .1s ease-in-out, border-color .1s ease-in-out;
          }

          .todo-toggle::after {
            left: 12px;
            width: 16px;
            height: 16px;
            background: #fff;
            border-radius: 50%;
            box-shadow: 0 2px 6px rgba(0, 0, 0, .25);
            transition: transform .3s cubic-bezier(.45, -.15, .1, 1.15);
          }

          .todo-toggle.is-active::before {
            background-color: #f00;
            border-color: #f00;
          }

          .todo-toggle.is-active::after {
            transform: translate(20px, -50%);
          }

          .has-todo {
            position: relative;
            outline: 1px dashed #f00;
            outline-offset: .15em;
          }

          .has-todo::after {
            position: absolute;
            top: 0;
            left: 0;
            z-index: 1000;
            max-width: 100%;
            padding: 2px 4px;
            color: #fff;
            font-size: 12px;
            background: #f00;
            content: "TODO: " attr(data-todo);
          }
        `;

    styleTag.setAttribute('type', 'text/css');
    styleTag.appendChild(document.createTextNode(styles));

    head.appendChild(styleTag);
  }

  function displayTodos() {
    todos.forEach((item) => {
      item.classList.toggle('has-todo');
    });
  }

  function addToggle() {
    const body = document.getElementsByTagName('body')[0];
    const toggleButton = document.createElement('button');

    toggleButton.type = 'button';
    toggleButton.classList.add('todo-toggle');

    toggleButton.appendChild(document.createTextNode('TODO'));

    body.appendChild(toggleButton);

    toggleButton.addEventListener('click', (event) => {
      event.preventDefault();
      toggleButton.classList.toggle('is-active');
      displayTodos();
    });
  }

  if (todos.length) {
    addStyles();
    addToggle();
  }
}

export default todo;
