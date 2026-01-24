<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- AOS Animation -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

{{-- Di @include('public.partials.header') atau section head --}}
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">

{{-- Di @include('public.partials.js') atau section scripts --}}
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>

<script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>

@push('after-script')
    <script>
        // Initialize AOS
        AOS.init({
            duration: 800,
            once: true
        });

        // Navbar Scroll Effect
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Date and Time
        function updateDateTime() {
            const now = new Date();

            const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September',
                'Oktober', 'November', 'Desember'
            ];

            const dayName = days[now.getDay()];
            const date = now.getDate();
            const month = months[now.getMonth()];
            const year = now.getFullYear();

            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');

            document.getElementById('current-date').textContent = `${dayName}, ${date} ${month} ${year}`;
            document.getElementById('current-time').textContent = `${hours}:${minutes}:${seconds} WIB`;
        }

        updateDateTime();
        setInterval(updateDateTime, 1000);

        // Visitor Counter Animation
        function animateCounter() {
            const counter = document.getElementById('visitorCount');
            const target = 125847;
            const duration = 2000;
            const start = 0;
            const increment = target / (duration / 16);
            let current = start;

            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    counter.textContent = target.toLocaleString('id-ID');
                    clearInterval(timer);
                } else {
                    counter.textContent = Math.floor(current).toLocaleString('id-ID');
                }
            }, 16);
        }

        // Trigger counter animation when section is visible
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    // animateCounter();
                    observer.unobserve(entry.target);
                }
            });
        });

        // observer.observe(document.querySelector('.stats-survey-section'));

        // Rating Stars
        // const stars = document.querySelectorAll('#ratingStars i');
        // let selectedRating = 0;

        // stars.forEach(star => {
        //     star.addEventListener('click', function() {
        //         selectedRating = parseInt(this.dataset.rating);
        //         updateStars(selectedRating);
        //     });

        //     star.addEventListener('mouseenter', function() {
        //         const rating = parseInt(this.dataset.rating);
        //         updateStars(rating);
        //     });
        // });

        // document.getElementById('ratingStars').addEventListener('mouseleave', function() {
        //     updateStars(selectedRating);
        // });

        function updateStars(rating) {
            stars.forEach(star => {
                const starRating = parseInt(star.dataset.rating);
                if (starRating <= rating) {
                    star.classList.add('active');
                } else {
                    star.classList.remove('active');
                }
            });
        }

        // Smooth Scroll
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    window.scrollTo({
                        top: target.offsetTop - 100,
                        behavior: 'smooth'
                    });
                }
            });
        });
    </script>
@endpush
