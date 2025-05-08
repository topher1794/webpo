
import { fetchData } from './login.js'

$(document).on('submit', '#registerFrm', async (e) => {
    e.preventDefault();
    let myData = $('#registerFrm').serialize();
    const params = new URLSearchParams(myData);
    const myObject = {};


    for (const [key, value] of params.entries()) {
        myObject[key] = value;
    }

    fetchData('newRegistration', 'POST', myObject)
        .then(createdUser => {
            console.log(createdUser)
            switch (createdUser.status) {
                case 409:
                    alert(createdUser.message, 'danger');
                    $('#email').prop('style', 'border-color: red')
                    break;
                case 400:
                    alert(createdUser.message, 'danger');
                    break;
                case 201:
                    alert(createdUser.message, 'success');
                default:
                    createdUser.status
            }

        })
        .catch(error => {
            // Handle error
            console.log('MY ERROR: ', createdUser);
        });
})

let keydownCount = 0;
$(document).on('keydown', '#password', (event) => {
    if (event.key.length === 1) { // Ensures only printable characters are counted
        keydownCount++;

    } else if (event.key === "Backspace") {
        if (keydownCount > 0) {
            keydownCount--
        }
    }

    if (keydownCount >= 6) {
        $('#password').prop('style', 'border-color: green; box-shadow: green');
        $('#passwordFeedBack').prop('hidden', true);
        $('#confirm-password').prop('readonly', false);
        $('#registerBtn').removeAttr('disabled');
    } else {
        $('#password').prop('style', 'border-color: red; ')
        $('#passwordFeedBack').removeAttr('hidden').text('password should have a minimum of 6 characters').prop('style', 'color: red; font-size: 0.700em');
        $('#confirm-password').prop('readonly', true);
        $('#registerBtn').prop('disabled', true);
    }
});

function alert(message, alertType) {
    console.log(alertType)
    $('#alert').removeAttr('class');
    $('#alert').prop('class', 'alert alert-' + alertType).text(message).fadeIn();
}


