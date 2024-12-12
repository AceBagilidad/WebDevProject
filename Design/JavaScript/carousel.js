let next = document.querySelector('.next');
let prev = document.querySelector('.prev');
const section = document.querySelector('.section-three');
const imageArray = ['./Image/VIELADA1.png', './Image/img-necklace.png', './Image/img-earrings.png', './Image/img-bracelet.png', './Image/img-anklet.png', ];

let currentIndex = 0; // Track the current index of the middle item

// Next button click event
next.addEventListener('click', function() {
    let items = document.querySelectorAll('.item');
    document.querySelector('.slide').appendChild(items[0]); // Move the first item to the end
    currentIndex = (currentIndex + 1) % imageArray.length; // Update the index
    updateContent();
});

// Previous button click event
prev.addEventListener('click', function() {
    let items = document.querySelectorAll('.item');
    document.querySelector('.slide').prepend(items[items.length - 1]); // Move the last item to the start
    currentIndex = (currentIndex - 1 + imageArray.length) % imageArray.length; // Update the index and ensure it's within bounds
    updateContent();
});

// Function to update the content visibility and background image
function updateContent() {
    let items = document.querySelectorAll('.item');

    // Hide all content initially
    items.forEach(item => {
        item.querySelector('.content').style.display = 'none';
    });

    // Show the content for the item in the middle (3rd child)
    items[2].querySelector('.content').style.display = 'block';

    // Update the background image based on the current index
    section.style.backgroundImage = `url(${imageArray[currentIndex]})`;
}

// Initialize the correct content visibility on page load
updateContent();