document.addEventListener('DOMContentLoaded', function() {
    // Filter and search functionality
    const filterBtn = document.getElementById('filterBtn');
    const searchInput = document.getElementById('searchInput');
    const categoryFilter = document.getElementById('categoryFilter');
    const sortSelect = document.getElementById('sortSelect');
    const productsContainer = document.getElementById('productsContainer');
    const productCards = document.querySelectorAll('.product-card');
    
    // Filter products based on search and category
    function filterProducts() {
        const searchTerm = searchInput.value.toLowerCase();
        const category = categoryFilter.value.toLowerCase();
        
        productCards.forEach(card => {
            const name = card.dataset.name;
            const cardCategory = card.dataset.category;
            const matchesSearch = name.includes(searchTerm);
            const matchesCategory = category === '' || cardCategory.includes(category);
            
            if (matchesSearch && matchesCategory) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }
    
    // Sort products
    function sortProducts() {
        const sortValue = sortSelect.value;
        const cardsArray = Array.from(productCards);
        
        cardsArray.sort((a, b) => {
            switch(sortValue) {
                case 'price_asc':
                    return parseFloat(a.dataset.price) - parseFloat(b.dataset.price);
                case 'price_desc':
                    return parseFloat(b.dataset.price) - parseFloat(a.dataset.price);
                case 'name_asc':
                    return a.dataset.name.localeCompare(b.dataset.name);
                case 'name_desc':
                    return b.dataset.name.localeCompare(a.dataset.name);
                case 'newest':
                default:
                    return parseFloat(b.dataset.date) - parseFloat(a.dataset.date);
            }
        });
        
        // Re-append sorted cards
        cardsArray.forEach(card => {
            productsContainer.appendChild(card);
        });
    }
    
    // Event listeners
    filterBtn.addEventListener('click', filterProducts);
    searchInput.addEventListener('keyup', filterProducts);
    categoryFilter.addEventListener('change', filterProducts);
    sortSelect.addEventListener('change', sortProducts);
    
    // Quick view modal functionality
    const modal = document.getElementById('productModal');
    const modalContent = modal.querySelector('.modal-body');
    const closeModal = document.querySelector('.close-modal');
    
    document.querySelectorAll('.btn-view').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const productCard = this.closest('.product-card');
            const productName = productCard.querySelector('h3').textContent;
            const productPrice = productCard.querySelector('.product-price').textContent;
            const productCategory = productCard.querySelector('.product-category')?.textContent || '';
            const productDescription = productCard.querySelector('.product-description').textContent;
            const productImage = productCard.querySelector('img')?.src || '';
            
            modalContent.innerHTML = `
                <div class="modal-product">
                    <div class="modal-product-image">
                        ${productImage ? `<img src="${productImage}" alt="${productName}">` : '<div class="no-image">No Image Available</div>'}
                    </div>
                    <div class="modal-product-info">
                        <h2>${productName}</h2>
                        ${productCategory ? `<p class="modal-category">${productCategory}</p>` : ''}
                        <p class="modal-price">${productPrice}</p>
                        <p class="modal-description">${productDescription}</p>
                        <button class="btn btn-add-to-cart">Add to Cart</button>
                    </div>
                </div>
            `;
            
            modal.style.display = 'block';
        });
    });
    
    closeModal.addEventListener('click', function() {
        modal.style.display = 'none';
    });
    
    window.addEventListener('click', function(e) {
        if (e.target === modal) {
            modal.style.display = 'none';
        }
    });
    
    // Add to cart functionality (placeholder)
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('btn-add-to-cart')) {
            e.preventDefault();
            alert('Product added to cart!');
            // In a real implementation, you would add to a cart system here
        }
    });
});