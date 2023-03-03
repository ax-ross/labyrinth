function createLabyrinthForm() {
    let heightEl = document.getElementById('height');
    let widthEl = document.getElementById('width');
    if (validateLabyrinthSize(widthEl, heightEl)) {
        document.getElementById('create-labyrinth-button').setAttribute('disabled', '')
        let labyrinth = document.getElementById('labyrinth');
        let labyrinthForm = document.createElement("form");
        labyrinthForm.setAttribute("id", "labyrinthForm");

        createSendLabyrinthFormButton(labyrinth);
        createInputStartPoint(labyrinthForm, widthEl, heightEl);
        createInputStopPoint(labyrinthForm, widthEl, heightEl);

        let labyrinthValidationError = document.createElement('div');
        labyrinthValidationError.style.color = 'red';
        labyrinthValidationError.setAttribute('id', 'labyrinthValidationError');
        labyrinth.appendChild(labyrinthValidationError);

        for (let i = 0; i < heightEl.value; i++) {
            let row = document.createElement("div");
            for (let j = 0; j < widthEl.value; j++) {
                let tmp = document.createElement("input");
                tmp.setAttribute('type', 'number');
                tmp.setAttribute('min', '0');
                tmp.setAttribute('max', '9');
                tmp.setAttribute('name', `labyrinth[${i}][${j}]`)
                row.appendChild(tmp);
            }
            labyrinthForm.appendChild(row);
        }
        labyrinth.appendChild(labyrinthForm);


    }
}

function createInputStartPoint(labyrinthForm, widthEl, heightEl) {

    let startPoint = document.createElement('div');

    let startPointLabel = document.createElement('label');

    startPointLabel.innerText = 'Введите координаты стартовой точки: ';

    let inputStartX = document.createElement('input');
    inputStartX.setAttribute('type', 'number');
    inputStartX.setAttribute('min', '0');
    inputStartX.setAttribute('max', `${widthEl.value - 1}`);
    inputStartX.setAttribute('name', 'start[]');
    let inputStartY = document.createElement('input');
    inputStartY.setAttribute('type', 'number');
    inputStartY.setAttribute('min', '0');
    inputStartY.setAttribute('max', `${heightEl.value - 1}`);
    inputStartY.setAttribute('name', 'start[]');


    startPointLabel.appendChild(inputStartX)
    startPointLabel.appendChild(inputStartY)

    startPoint.appendChild(startPointLabel);

    labyrinthForm.appendChild(startPoint);



}

function createInputStopPoint(labyrinthForm, widthEl, heightEl) {

    let stopPoint = document.createElement('div');
    stopPoint.setAttribute('id', 'stop-point');
    let stopPointLabel = document.createElement('label');

    stopPointLabel.innerText = 'Введите координаты финишной точки: ';

    let inputStopX = document.createElement('input');
    inputStopX.setAttribute('type', 'number');
    inputStopX.setAttribute('min', '0');
    inputStopX.setAttribute('max', `${widthEl.value - 1}`);
    inputStopX.setAttribute('name', 'stop[]');
    let inputStopY = document.createElement('input');
    inputStopY.setAttribute('type', 'number');
    inputStopY.setAttribute('min', '0');
    inputStopY.setAttribute('max', `${heightEl.value - 1}`);
    inputStopY.setAttribute('name', 'stop[]');


    stopPointLabel.appendChild(inputStopX)
    stopPointLabel.appendChild(inputStopY)

    stopPoint.appendChild(stopPointLabel);

    labyrinthForm.appendChild(stopPoint);

    let distance = document.createElement('div');
    distance.setAttribute('id', 'distance');
    stopPoint.append(distance);
}

function createSendLabyrinthFormButton(labyrinth) {
    let sendLabyrinthButton = document.createElement("button");
    sendLabyrinthButton.innerText = "Рассчитать маршрут";
    sendLabyrinthButton.setAttribute("onclick", "sendLabyrinth()")
    labyrinth.appendChild(sendLabyrinthButton)
}

function validateLabyrinthSize(widthEl, heightEl) {
    let widthIsValid = true;
    let heightIsValid = true;
    if (isNaN(widthEl.value) || widthEl.value < 1 || widthEl.value > 100) {
        let widthValidationError = document.getElementById("width-validation-error");
        widthValidationError.innerHTML = "Ширина лабиринта должна быть целым числом от 1 до 100. Для работы с лабиринтом большей ширины воспользуйтесь консольным интерфейсом.";
        widthValidationError.style.color = 'red';
        document.getElementById('labyrinth-width').appendChild(widthValidationError);
        widthIsValid = false;
    }
    if (isNaN(heightEl.value) || heightEl.value < 1 || heightEl.value > 100) {
        let heightValidationError = document.getElementById("height-validation-error");
        heightValidationError.innerHTML = "Ширина лабиринта должна быть целым числом от 1 до 100. Для работы с лабиринтом большей ширины воспользуйтесь консольным интерфейсом.";
        heightValidationError.style.color = 'red';
        document.getElementById('labyrinth-height').appendChild(heightValidationError);
        heightIsValid = false;
    }
    if (heightIsValid) {
        document.getElementById("height-validation-error").innerHTML = '';
    }
    if (widthIsValid) {
        document.getElementById("width-validation-error").innerHTML = '';
    }
    return widthIsValid && heightIsValid;
}

function sendLabyrinth() {

    let formEl = document.forms.labyrinthForm;
    let formData = new FormData(formEl);

    document.querySelectorAll('input').forEach(input => {
        input.style.backgroundColor = 'white';
    });

    fetch("http://127.0.0.1/src/", {method: "POST", body: formData}).then((response) => {
        if (response.ok) {
            return response.json();
        }
        return Promise.reject(response);
    }).then((data) => {
        data.path.forEach(function (item, index, arr) {
            document.getElementsByName(`labyrinth[${arr[index][0]}][${arr[index][1]}]`)[0].style.backgroundColor = '#90ee90';
        });
        document.getElementById('labyrinthValidationError').innerHTML = '';
        document.getElementById('distance').innerHTML = `<br> Неименьшее кол-во ходов для указанного пути: <b>${data.distance}</b>`;
    }).catch((response) => {
        response = response.json();
        response.then((data) => {
            document.getElementById('labyrinthValidationError').innerHTML = data.message;
            document.getElementById('distance').innerHTML = '';
        })
    })
}

let heightEl = document.getElementById('height');
let widthEl = document.getElementById('width');
heightEl.addEventListener("change", (event) => {
    let labyrinth = document.getElementById('labyrinth');
    labyrinth.innerHTML = "";
    document.getElementById('create-labyrinth-button').disabled = false;
});
widthEl.addEventListener("change", (event) => {
    let labyrinth = document.getElementById('labyrinth');
    labyrinth.innerHTML = "";
    document.getElementById('create-labyrinth-button').disabled = false;
});