@extends('layouts.eticket')
<meta name="csrf-token" content="{{ csrf_token() }}">
@section('content')
<div id="adCarousel" class="carousel slide mb-4" data-bs-ride="carousel">
    <div class="carousel-indicators">
        <button type="button" data-bs-target="#adCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
        <button type="button" data-bs-target="#adCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
        <button type="button" data-bs-target="#adCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
    </div>
    <div class="carousel-inner">
        <div class="carousel-item active">
            <img src="https://picsum.photos/id/1/2000/1000" class="d-block w-100 h-100 object-fit-cover" alt="Slide 1">
        </div>
        <div class="carousel-item">
            <img src="https://picsum.photos/id/2/2000/1000" class="d-block w-100 h-100 object-fit-cover" alt="Slide 2">
        </div>
        <div class="carousel-item">
            <img src="https://picsum.photos/id/3/2000/1000" class="d-block w-100 h-100 object-fit-cover" alt="Slide 3">
        </div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#adCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#adCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </button>
</div>

<div class="album">
    <div class="container">
        <h3><b>Konser Terdekat</b></h3>
        <div id="konserCarousel" class="carousel slide mb-4 p-0" data-bs-ride="false">
            <div class="carousel-inner carousel-container" data-name="konserDekat" data-events='@json($konserDekat)'>

            </div>
            <button class="carousel-control-prev w-auto ps-2 opacity-100" type="button" data-bs-target="#konserCarousel" data-bs-slide="prev">
                <i class="fa-solid fa-arrow-left text-body bg-body border fs-5 p-1 rounded-circle"></i>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next w-auto pe-2 opacity-100" type="button" data-bs-target="#konserCarousel" data-bs-slide="next">
                <i class="fa-solid fa-arrow-right text-body bg-body border fs-5 p-1 rounded-circle"></i>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
        <h3><b>Rekomendasi Konser Untukmu</b></h3>
        <div id="rekomCarousel" class="carousel slide mb-4 p-0" data-bs-ride="false">
            <div class="carousel-inner carousel-container" data-name="rekomKonser" data-events='@json($recomEvents)'>

            </div>
            <button class="carousel-control-prev w-auto ps-2 opacity-100" type="button" data-bs-target="#rekomCarousel" data-bs-slide="prev">
                <i class="fa-solid fa-arrow-left text-body bg-body border fs-5 p-1 rounded-circle"></i>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next w-auto pe-2 opacity-100" type="button" data-bs-target="#rekomCarousel" data-bs-slide="next">
                <i class="fa-solid fa-arrow-right text-body bg-body border fs-5 p-1 rounded-circle"></i>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
        <h3><b>Penyelenggara Favorit</b></h3>
        <div id="penyelenggaraCarousel" class="carousel slide mb-4 p-0" data-bs-ride="false">
            <div class="carousel-inner carousel-container" data-name="penyelenggara" data-events='@json($penyelenggara)'>

            </div>
            <button class="carousel-control-prev w-auto ps-2 opacity-100" type="button" data-bs-target="#penyelenggaraCarousel" data-bs-slide="prev">
                <i class="fa-solid fa-arrow-left text-body bg-body border fs-5 p-1 rounded-circle"></i>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next w-auto pe-2 opacity-100" type="button" data-bs-target="#penyelenggaraCarousel" data-bs-slide="next">
                <i class="fa-solid fa-arrow-right text-body bg-body border fs-5 p-1 rounded-circle"></i>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>
</div>

@if($purchases && $purchases->isNotEmpty())
<div class="dropdown position-fixed bottom-0 end-0 mb-3 me-3 bd-mode-toggle">
    <div class="card shadow" style="width: 300px; max-width: 100%;">
        <div class="card-body">
            <h5 class="fw-bold">Daftar Pembelian Tertunda</h5>
            @foreach($purchases as $purchase)
                <form action="{{ route('eticket.purchase', ['id' => $purchase->id]) }}" method="POST" class="d-inline">
                    @csrf
                    <input type="hidden" name="purchase-menu" value="check-purchase">
                    <a href="#" class="mt-2 d-flex align-items-center gap-3 text-decoration-none text-dark"
                    onclick="this.closest('form').submit(); return false;">
                        <img src="{{ asset('storage/' . $purchase->concert->gambar) }}" 
                            class="flex-shrink-0 rounded"
                            style="width: 60px; height: 60px; object-fit: cover;">
                        <div class="w-100">
                            <p class="text-warning mb-0 fw-bold" id="timer"></p>
                            <p class="mb-0 text-truncate d-inline-block w-100" style="max-width: 200px;">
                                {{ $purchase->concert->event->nama }}
                            </p>
                            <p class="mb-0 fw-light">
                                <i class="fa-solid fa-ticket fa-fw fs-6"></i>
                                {{ $purchase->jumlah_tiket }} tiket
                                <span class="fw-bold">Rp{{ number_format($purchase->total_tagihan, 0, ',', '.') }}</span>
                            </p>
                        </div>
                    </a>
                </form>
                <script>
                    let waktuPembelian = new Date("{{ $purchase->waktu_pembelian }}").getTime();
                    let expiryTime = waktuPembelian + (24 * 60 * 60 * 1000); // Add 24 hours

                    function updateTimer() {
                        let now = new Date().getTime();
                        let remaining = expiryTime - now;

                        if (remaining <= 0) {
                            document.getElementById("countdown").innerHTML = "<strong>Pembayaran telah kadaluarsa!</strong>";
                            return;
                        }

                        let hours = Math.floor((remaining / (1000 * 60 * 60)) % 24);
                        let minutes = Math.floor((remaining / (1000 * 60)) % 60);
                        let seconds = Math.floor((remaining / 1000) % 60);

                        document.getElementById("timer").innerText = `${hours}:${minutes}:${seconds}`;
                    }

                    setInterval(updateTimer, 1000);
                    updateTimer();
                </script>
            @endforeach
        </div>
    </div>
</div>
@endif
@endsection

@section('js')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // const carousels = document.querySelectorAll("#konserCarousel, #rekomCarousel, #penyelenggaraCarousel");

        // //Tombol carousel
        // carousels.forEach((carousel) => {
        //     const prevBtn = carousel.querySelector(".carousel-control-prev");
        //     const nextBtn = carousel.querySelector(".carousel-control-next");

        //     function updateButtons() {
        //         let activeItem = carousel.querySelector(".konser-item.active, .rekom-item.active, .penyelenggara-item.active");
        //         let items = carousel.querySelectorAll(".konser-item, .rekom-item, .penyelenggara-item");
        //         let index = Array.from(items).indexOf(activeItem);

        //         prevBtn.style.display = (index === 0) ? "none" : "block";
        //         nextBtn.style.display = (index === items.length - 1) ? "none" : "block";
        //     }

        //     carousel.addEventListener("slid.bs.carousel", updateButtons);
        //     updateButtons(); // Run on load
        // });

        function fetchCarouselItems(carouselContainer) {
            let items = JSON.parse(carouselContainer.getAttribute("data-events"));
            let itemsType = carouselContainer.getAttribute("data-name");

            let itemsPerRow = 4;
            if (itemsType === "penyelenggara") {
                itemsPerRow = 6;
            }

            if (window.innerWidth < 768) {
                itemsPerRow = 2; // Small screens
            } else if (window.innerWidth < 992) {
                itemsPerRow = 3; // Medium screens
            }

            // Clear carousel content
            carouselContainer.innerHTML = "";

            let rowDiv;
            items.forEach((item, index) => {
                // Create new row every `itemsPerRow` items
                if (index % itemsPerRow === 0) {
                    rowDiv = document.createElement("div");
                    rowDiv.classList.add("row");

                    let carouselItem = document.createElement("div");
                    carouselItem.classList.add("carousel-item", "h-auto");

                    if (carouselContainer.children.length === 0) {
                        carouselItem.classList.add("active"); // First item active
                    }

                    carouselItem.appendChild(rowDiv);
                    carouselContainer.appendChild(carouselItem);
                }

                // Create event card
                let eventCard = document.createElement("div");
                if (itemsType === "penyelenggara") {
                    eventCard.classList.add("col-lg-2", "col-6", "col-md-4");
                    eventCard.innerHTML = `
                        <a href="/eticket/show/choir/${item.id}" class="text-decoration-none w-100">
                            <div class="card text-center border-0 h-100 p-3">
                                <div class="d-flex justify-content-center">
                                    <img src="/storage/${item.logo}" class="rounded-circle img-fluid" style="width: 120px; height: 120px; object-fit: cover;" alt="${item.nama}">
                                </div>
                                <div class="mt-3">
                                    <h6 class="card-title truncate-2-lines">
                                        ${item.nama}
                                    </h6>
                                </div>
                            </div>
                        </a>
                    `;
                } else {
                    eventCard.classList.add("col-lg-3", "col-6", "col-md-4");
                    eventCard.innerHTML = `
                        <a href="/eticket/show/${item.concert.id}" class="text-decoration-none">
                            <div class="card h-100">
                                <img src="/storage/${item.concert.gambar}" class="card-img-top img-fluid" alt="${item.nama}" style="aspect-ratio: 16/9; object-fit: cover;">
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title text-truncate">${item.nama}</h5>
                                    <p class="card-text mb-0">${item.tanggal_mulai}</p>
                                    <p class="card-text"><b>Rp${item.hargaMulai}</b></p>
                                    <h6 class="card-title border-top pt-2 text-truncate">${item.choir.nama}</h6>
                                </div>
                            </div>
                        </a>
                    `;
                }

                rowDiv.appendChild(eventCard);
            });

        }

        function updateAllCarousels() {
            document.querySelectorAll(".carousel-container").forEach(fetchCarouselItems);
        }

        updateAllCarousels();
        // Run when window resizes
        window.addEventListener("resize", updateAllCarousels);
    });
</script>
@endsection