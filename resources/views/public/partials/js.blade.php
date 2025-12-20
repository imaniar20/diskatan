<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>

<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>

<!-- Custom JS -->
<script>
    // Initialize carousel
    document.addEventListener('DOMContentLoaded', function() {
        var myCarousel = document.querySelector('#bannerCarousel');
        if (myCarousel) {
            var carousel = new bootstrap.Carousel(myCarousel, {
                interval: 3000,
                wrap: true
            });
        }
    });

    document.addEventListener("DOMContentLoaded", function() {
        AOS.init({
            duration: 800,
            once: false,
            mirror: true
        });
    });

    document.addEventListener("DOMContentLoaded", function() {
        const elements = document.querySelectorAll(".fade-up");

        const observer = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add("show");
                } else {
                    entry.target.classList.remove("show"); // ⬅️ reset
                }
            });
        }, {
            threshold: 0.2
        });

        elements.forEach(el => observer.observe(el));
    });

    $(document).ready(function() {
        $('.datatable').each(function() {
            if (!$.fn.DataTable.isDataTable(this)) {
                new DataTable(this);
            }
        });
    });
</script>
