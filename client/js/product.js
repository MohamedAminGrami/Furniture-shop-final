document.addEventListener('DOMContentLoaded', function() {
    // Quantity selector functionality
    const minusBtn = document.querySelector('.quantity-btn.minus');
    const plusBtn = document.querySelector('.quantity-btn.plus');
    const quantityInput = document.querySelector('.quantity-input');
    
    minusBtn.addEventListener('click', function(e) {
        e.preventDefault();
        let value = parseInt(quantityInput.value);
        if (value > 1) {
            quantityInput.value = value - 1;
        }
    });
    
    plusBtn.addEventListener('click', function(e) {
        e.preventDefault();
        let value = parseInt(quantityInput.value);
        quantityInput.value = value + 1;
    });
    
    // Thumbnail click functionality
    const thumbnails = document.querySelectorAll('.thumbnail');
    const mainImage = document.querySelector('.main-image img');
    
    thumbnails.forEach(thumb => {
        thumb.addEventListener('click', function() {
            // Remove active class from all thumbnails
            thumbnails.forEach(t => t.classList.remove('active'));
            // Add active class to clicked thumbnail
            this.classList.add('active');
            // Update main image (in a real implementation, this would change the image)
            // mainImage.src = this.querySelector('img').src;
        });
    });
    
    // Add to cart functionality
    const addToCartBtn = document.querySelector('.btn-add-to-cart');
    addToCartBtn.addEventListener('click', function() {
        const productId = window.location.pathname.split('=')[1];
        const quantity = parseInt(quantityInput.value);
        
        // In a real implementation, you would add to cart here
        alert(`Added ${quantity} item(s) to cart!`);
        
        // Example AJAX call:
        /*
        fetch('add_to_cart.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                product_id: productId,
                quantity: quantity
            })
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
        });
        */
    });
});