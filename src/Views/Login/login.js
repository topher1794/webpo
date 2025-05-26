$(document).ready(() => {
    $('.alert').hide();
})

$(document).on('submit', '#loginFrm', async function (e) {
    e.preventDefault();



    // Example of making a POST request
    const newUser = {
        InputEmail: $('#InputEmail').val(),
        InputPassword: $('#InputPassword').val()
    };

    fetchData('userAuthen', 'POST', newUser)
        .then(createdUser => {
            if (createdUser.status == 'error') {

                $('.alert').fadeIn('slow');
                $('.alert-text').text(createdUser.message)

                setTimeout(() => {
                    $('.alert').fadeOut('slow');

                }, 3000);
            } else {
                window.location.href = 'home';
            }

        })
        .catch(error => {
            // Handle error
            console.log('MY ERROR: ', createdUser);
        });

})


export async function fetchData(endpoint, method, data = null) {
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