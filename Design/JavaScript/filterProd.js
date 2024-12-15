// Store the raw product data globally
let productData = [];
const productsContainer = document.querySelector('.prod-container');
const categoryFilter = document.getElementById('categoryFilter');
const sortFilter = document.getElementById('sortFilter');
let filteredProducts = []; // This will store products filtered by category

// Function to render the products (both for initial load and sorting/filtering)
// Function to render the products (both for initial load and sorting/filtering)
function renderProducts(products) {
    productsContainer.innerHTML = ''; // Clear previous products

    if (products.length === 0) {
        productsContainer.innerHTML = '<p>No products found...</p>';
        return;
    }

    products.forEach((product) => {
        const productElement = document.createElement('div');
        productElement.classList.add('prod');
        productElement.setAttribute('data-category', product.category);
        productElement.setAttribute('data-name', product.name);
        productElement.setAttribute('data-price', product.price);
        productElement.setAttribute('data-rating', product.rating);
        productElement.setAttribute('data-id', product.id); // Store the product id in the data attribute

        const stars = getRating(product.rating);

        productElement.innerHTML = `
            <img src="${product.image_url}" alt="${product.name}">
            <div class="des">
                <span>LuXe</span>
                <h2>${product.name}</h2>
                <div class="star">${stars}</div>
                <h3>₱${product.price}</h3>
            </div>
        `;

        // Add click event listener for redirection
        productElement.addEventListener('click', () => {
            // Get the product id from the data-id attribute
            const productId = productElement.getAttribute('data-id');

            // Log the product id to the console
            console.log('Product ID clicked:', productId);

            // Redirect to the product page
            window.location.href = `sproduct.php?id=${productId}`;
        });

        productsContainer.appendChild(productElement);
    });
}

function getRating(rating) {
    let ratingHtml = '';
    for (let i = 0; i < 5; i++) {
        ratingHtml += i < rating ? '<i class="fas fa-star"></i>' : '<i class="far fa-star"></i>';
    }
    return ratingHtml;
}

// Function to load initial products from the PHP-rendered HTML into the `productData` array
function loadInitialProducts() {
    const productElements = document.querySelectorAll('.prod');

    productElements.forEach((productElement) => {
        const product = {
            id: productElement.dataset.id,
            name: productElement.querySelector('h2').textContent,
            category: productElement.dataset.category,
            price: parseFloat(productElement.getAttribute('data-price')),
            rating: parseInt(productElement.getAttribute('data-rating')),
            image_url: productElement.querySelector('img').src,
        };
        productData.push(product);
    });

    filteredProducts = [...productData]; // Initially, show all products
    renderProducts(filteredProducts); // Render the products initially
}

// Handle category filter change
categoryFilter.addEventListener('change', () => {
    handleCategoryChange();
});

function handleCategoryChange() {
    const selectedCategory = categoryFilter.value;
    filteredProducts = selectedCategory
        ? productData.filter((product) => product.category === selectedCategory)
        : [...productData]; // Reset to all products if no category is selected
    renderProducts(filteredProducts); // Render filtered products
}

// Sort functionality
sortFilter.addEventListener('change', () => {
    let sortedProducts = [...filteredProducts]; // Sort only the filtered products

    if (sortFilter.value === 'a-z') {
        sortedProducts.sort((a, b) => a.name.localeCompare(b.name));
    } else if (sortFilter.value === 'price-low-high') {
        sortedProducts.sort((a, b) => a.price - b.price);
    } else if (sortFilter.value === 'price-high-low') {
        sortedProducts.sort((a, b) => b.price - a.price);
    } else if (sortFilter.value === 'popularity') {
        sortedProducts.sort((a, b) => b.rating - a.rating);
    }

    renderProducts(sortedProducts); // Re-render products with sorting
});

// Load the initial products from PHP rendering
document.addEventListener('DOMContentLoaded', () => {
    loadInitialProducts();
    handleCategoryChange();
});
