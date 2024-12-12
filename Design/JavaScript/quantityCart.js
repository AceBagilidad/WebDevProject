// document.querySelectorAll('.quantity-form').forEach(form => {
//     form.addEventListener('submit', function (e) {
//         e.preventDefault();

//         const formData = new FormData(this);

//         fetch('./action/manageCart.php', {
//             method: 'POST',
//             body: formData,
//         })
//         .then(response => response.json())
//         .then(data => {
//             console.log(data); // Debug response
//             if (data.success) {
//                 // Update the cart UI
//                 const subtotalElement = document.querySelector(`#subtotal-${data.product_id}`);
//                 const totalElement = document.querySelector('#total');

//                 if (subtotalElement) {
//                     subtotalElement.innerText = `₱${data.subtotal.toFixed(2)}`;
//                 }

//                 if (totalElement) {
//                     totalElement.innerText = `₱${data.total.toFixed(2)}`;
//                 }
//             } else {
//                 console.error(data.error || 'Failed to update cart.');
//             }
//         })
//         .catch(error => {
//             console.error("Fetch error:", error);
//         });
//     });
// });
