
        document.addEventListener("DOMContentLoaded", function () {
            console.log("Le script JS est bien chargé !");
        
            // Slideshow functionality
            let slideIndex = 0;
            const slides = document.querySelectorAll('.slide');
        
            function showSlides() {
                // Vérifie s'il y a des slides
                if (slides.length === 0) {
                    console.error("Aucune slide trouvée !");
                    return;
                }
        
                // Hide all slides
                slides.forEach(slide => slide.classList.remove('active'));
        
                // Move to next slide
                slideIndex++;
                if (slideIndex >= slides.length) {
                    slideIndex = 0;
                }
        
                // Show current slide
                slides[slideIndex].classList.add('active');
        
                // Change slide every 5 seconds
                setTimeout(showSlides, 5000);
            }
        
            // Start slideshow si on a au moins un élément
            if (slides.length > 0) {
                showSlides();
            } else {
                console.error("Aucune image de slide trouvée !");
            }
        });
        
