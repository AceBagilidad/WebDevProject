function submitReview(productId) {
    // Check if the user is logged in using the server-passed JavaScript variable
    if (!userLoggedIn) {
        alert('Please log in to submit a review.');
        return; // Exit if not logged in
    }

    // Get the rating and comment values from the form
    const rating = document.querySelector('input[name="rating"]:checked')?.value;
    const comment = document.getElementById('comment-input').value;

    // Validate the rating
    if (!rating) {
        alert('Please select a rating.');
        return;
    }

    // Validate the comment
    if (!comment.trim()) {
        alert('Please enter a comment.');
        return;
    }

    // Prepare the data to send in the request body
    const reviewData = {
        product_id: productId,
        rating: rating,
        comment: comment,
        user_id: userId // Add userId here
    };

    // Send the review data to the server via fetch
    fetch('submitReview.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(reviewData)
    })
    .then(response => response.json())
    .then(data => {
        // Handle the response from the server
        if (data.success) {
            alert('Your review has been submitted!');
            location.reload(); // Reload the page to show the updated reviews
        } else if (data.redirect) {
            window.location.href = data.redirect;
        } else {
            alert(data.error || 'An error occurred.');
        }
    })
    .catch(error => console.error('Error:', error));
}