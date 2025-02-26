document.querySelectorAll('.like-btn').forEach(button => {
    button.addEventListener('click', function(event) {
        event.preventDefault();  // Prevent form submission

        const recipeId = this.getAttribute('data-recipe-id');
        const likeCountSpan = this.querySelector('.like-count');
        const button = this;

        console.log('Recipe ID:', recipeId);

        // Send AJAX request
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'like.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        xhr.onload = function() {
            console.log('AJAX Response:', xhr.responseText);

            if (xhr.status === 200) {
                try {
                    const response = JSON.parse(xhr.responseText);
                    if (response.error) {
                        alert(response.error);  // Display error if something went wrong
                    } else {
                        // Update the like count on the button
                        likeCountSpan.textContent = response.new_like_count;
                        button.classList.toggle('btn-success');  // Toggle button color to green
                    }
                } catch (e) {
                    console.error('Invalid JSON Response:', e);
                }
            } else {
                alert('Something went wrong, please try again later.');
            }
        };

        // Send the recipe_id in the POST request
        xhr.send('recipe_id=' + encodeURIComponent(recipeId));
    });
});