
// switch-color costume homme
document.addEventListener('DOMContentLoaded', function () {
    const colorOptions = document.querySelectorAll('.color-option');
    const selectedColorText = document.getElementById('selected-color');

    colorOptions.forEach(option => {
        option.addEventListener('click', function () {
            // Retirer la classe active de toutes les options
            colorOptions.forEach(opt => opt.classList.remove('active'));

            // Ajouter la classe active à l'option cliquée
            this.classList.add('active');

            // Mettre à jour le texte de la couleur sélectionnée
            const colorName = this.getAttribute('title');
            selectedColorText.textContent = colorName;
        });
    });
});


// color-switch pants casual femme
document.addEventListener('DOMContentLoaded', function () {
    const colorOptions = document.querySelectorAll('.color-option');
    const selectedColorText = document.getElementById('selected-color');
    const productImage = document.getElementById('product-image');

    colorOptions.forEach(option => {
        option.addEventListener('click', function () {
            // Retirer la classe active de toutes les options
            colorOptions.forEach(opt => opt.classList.remove('active'));

            // Ajouter la classe active à l'option cliquée
            this.classList.add('active');

            // Mettre à jour le texte de la couleur sélectionnée
            selectedColorText.textContent = this.getAttribute('title');

            // Fade-out l'image
            productImage.style.opacity = 0;

            // Après le fade-out, changer l'image et fade-in
            setTimeout(() => {
                productImage.src = this.getAttribute('data-src');
                productImage.style.opacity = 1;
            }, 200); // 200ms correspond à la moitié de la durée de transition
        });
    });
});

       
        const sizeOptions = document.querySelectorAll('.size-option');
        sizeOptions.forEach(option => {
            option.addEventListener('click', () => {
                const isSelected = option.classList.contains('bg-gray-300');

             
                sizeOptions.forEach(o => o.classList.remove('bg-gray-300', 'text-gray-800'));

              
                if (!isSelected) {
                    option.classList.add('bg-gray-300', 'text-gray-800');
                }
            });
        });

        const slides = document.querySelectorAll('.slide');
        let currentIndex = 0;

        function showSlide(index) {
        slides.forEach((slide, i) => {
            if(i === index) {
            slide.style.opacity = '1';
            slide.style.pointerEvents = 'auto';
            } else {
            slide.style.opacity = '0';
            slide.style.pointerEvents = 'none';
            }
        });
        }

        // Flèches
        document.getElementById('prev').addEventListener('click', () => {
        currentIndex = (currentIndex - 1 + slides.length) % slides.length;
        showSlide(currentIndex);
        });

        document.getElementById('next').addEventListener('click', () => {
        currentIndex = (currentIndex + 1) % slides.length;
        showSlide(currentIndex);
        });

        // Auto-slide toutes les 10 secondes
        setInterval(() => {
        currentIndex = (currentIndex + 1) % slides.length;
        showSlide(currentIndex);
        }, 10000);

        // Initialisation
        showSlide(currentIndex);

          AOS.init({
    duration: 1000, // durée de l'animation
    once: true,     // animation une seule fois au scroll
  });
  document.addEventListener('DOMContentLoaded', () => {
  if (AOS) {
    AOS.init({
      duration: 1000, // durée des animations
      once: true      // animation visible une seule fois
    });
  }
});


  
