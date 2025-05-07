// $(document).on('submit', '#loginFrm', async function (e) {
//     e.preventDefault();
//     // userAuthen

//     let $data = $('#loginFrm').serialize();

//     let myResult = await AjaxRequest('userAuthen', $data);

//     console.log(myResult)
// })

// async function AjaxRequest(url, data) {
//     const result = await

//         $.ajax({
//             method: 'POST',
//             url: url,
//             data: data
//         })

//     return result;
// }

$(document).ready(() => {
    $('.alert').hide();
})

$(document).on('submit', '#loginFrm', async function (e) {
    e.preventDefault();
    async function fetchData(endpoint, method, data = null) {
        const url = `${endpoint}`; // Assuming your API routes are under /api/

        const options = {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                // Add any other necessary headers (e.g., Authorization)
            },
            body: data ? JSON.stringify(data) : null,
        };

        try {
            const response = await fetch(url, options);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            const responseData = await response.json();
            return responseData;
        } catch (error) {
            console.error('Error fetching data:', error);
            throw error; // Re-throw the error for the calling code to handle
        }
    }


    // Example of making a POST request
    const newUser = {
        InputEmail: $('#InputEmail').val(),
        InputPassword: $('#InputPassword').val()
    };
    fetchData('userAuthen', 'POST', newUser)
        .then(createdUser => {
            if (createdUser.status == 'error') {

                $('.alert').fadeIn('slow');
                $('.alert').text(createdUser.message)

                setTimeout(() => {
                    $('.alert').fadeOut('slow');

                }, 3000);
            } else {
                window.location.href = 'dashboard';
            }

        })
        .catch(error => {
            // Handle error
            console.log('MY ERROR: ', createdUser);
        });



})

// Example of making a PUT request
// const updatedUser = { name: 'Jane Doe', email: 'jane.doe@example.com' };
// fetchData('users/123', 'PUT', updatedUser)
//     .then(updatedUser => {
//         console.log('Updated User:', updatedUser);
//     })
//     .catch(error => {
//         // Handle error
//     });

// Example of making a DELETE request
// fetchData('users/123', 'DELETE')
//     .then(response => {
//         console.log('Delete Response:', response);
//     })
//     .catch(error => {
//         // Handle error
//     });