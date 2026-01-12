@extends('admin.layouts.app')
@section('Content')
    <div class="row">
        <div class="col-12 col-lg-8 col-md-8 mb-4">
            <div class="card mb-4">
                <div class="d-flex align-items-end row">
                    <div class="col-sm-7">
                        <div class="card-body">
                            <h5 class="card-title text-primary">Selamat Datang! 🎉</h5>
                            <div class="row">
                                <div class="col-6">
                                    <p class="mb-4">
                                        Total Agenda <span class="fw-bold">{{ $agenda }} agenda</span>
                                    </p>

                                    <a href="/admin-agenda" class="btn btn-sm btn-outline-warning">Kelola Agenda</a>
                                </div>
                                <div class="col-6">
                                    <p class="mb-4">
                                        Total Berita <span class="fw-bold">{{ $news }} berita</span>
                                    </p>

                                    <a href="/admin-berita" class="btn btn-sm btn-outline-success">Kelola Berita</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-5 text-center text-sm-left">
                        <div class="card-body pb-0 px-0 px-md-4">
                            <img src="../assets/img/illustrations/man-with-laptop-light.png" height="140"
                                alt="View Badge User" data-app-dark-img="illustrations/man-with-laptop-dark.png"
                                data-app-light-img="illustrations/man-with-laptop-light.png" />
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="row row-bordered g-0">
                    <div class="col-md-8">
                        <h5 class="card-header m-0 me-2 pb-3">Grafik Agenda</h5>
                        <div id="totalRevenueChart" class="px-2"></div>
                    </div>
                    <div class="col-md-4">
                        <div class="card-body">
                            <div class="text-center">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-primary" type="button" id="growthReportId"
                                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        {{ date('Y') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div id="growthChart"></div>
                        <div class="text-center fw-semibold pt-3 mb-2">Total Agenda</div>

                        <div class="d-flex px-xxl-4 px-lg-2 p-4 gap-xxl-3 gap-lg-1 gap-3 justify-content-between">
                            <div class="d-flex">
                                <div class="me-2">
                                    <span class="badge bg-label-primary p-2"><i
                                            class="bx bx-calendar text-primary"></i></span>
                                </div>
                                <div class="d-flex flex-column">
                                    <small>{{ date('Y') - 1 }}</small>
                                    <h6 class="mb-0">{{ $agendaBefore }} agenda</h6>
                                </div>
                            </div>
                            <div class="d-flex">
                                <div class="me-2">
                                    <span class="badge bg-label-info p-2"><i class="bx bx-calendar text-info"></i></span>
                                </div>
                                <div class="d-flex flex-column">
                                    <small>{{ date('Y') }}</small>
                                    <h6 class="mb-0">{{ $agendaAfter }} agenda</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="row row-bordered g-0">
                    <div class="col-md-8">
                        <h5 class="card-header m-0 me-2 pb-3">Grafik Berita</h5>
                        <div id="totalRevenueChartNews" class="px-2"></div>
                    </div>
                    <div class="col-md-4">
                        <div class="card-body">
                            <div class="text-center">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-primary" type="button" id="growthReportId"
                                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        {{ date('Y') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div id="growthChartNews"></div>
                        <div class="text-center fw-semibold pt-3 mb-2">Total Berita</div>

                        <div class="d-flex px-xxl-4 px-lg-2 p-4 gap-xxl-3 gap-lg-1 gap-3 justify-content-between">
                            <div class="d-flex">
                                <div class="me-2">
                                    <span class="badge bg-label-primary p-2"><i
                                            class="bx bx-calendar text-primary"></i></span>
                                </div>
                                <div class="d-flex flex-column">
                                    <small>{{ date('Y') - 1 }}</small>
                                    <h6 class="mb-0">{{ $newsBefore }} berita</h6>
                                </div>
                            </div>
                            <div class="d-flex">
                                <div class="me-2">
                                    <span class="badge bg-label-info p-2"><i class="bx bx-calendar text-info"></i></span>
                                </div>
                                <div class="d-flex flex-column">
                                    <small>{{ date('Y') }}</small>
                                    <h6 class="mb-0">{{ $newsAfter }} berita</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="col-12 col-lg-4 col-md-4 order-1">
            <div class="row">
                <div class="col-lg-6 col-md-12 col-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between">
                                <div class="avatar flex-shrink-0">
                                    <div class="avatar-initial rounded bg-label-success">
                                        <i class='bx bx-user'></i>
                                    </div>
                                </div>
                                <div class="dropdown">
                                    <button class="btn p-0" type="button" id="cardOpt3" data-bs-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false">
                                        <i class="bx bx-dots-vertical-rounded"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt3">
                                        <a class="dropdown-item" href="/admin-user">Lihat Lebih Banyak</a>
                                    </div>
                                </div>
                            </div>
                            <span class="fw-semibold d-block mb-1">User</span>
                            <h3 class="card-title mb-2">{{ $user }} orang</h3>
                            {{-- <small class="text-success fw-semibold"><i class="bx bx-up-arrow-alt"></i> +72.80%</small> --}}
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12 col-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between">
                                <div class="avatar flex-shrink-0">
                                    <div class="avatar-initial rounded bg-label-primary">
                                        <i class='bx bx-building'></i>
                                    </div>
                                </div>
                                <div class="dropdown">
                                    <button class="btn p-0" type="button" id="cardOpt3" data-bs-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false">
                                        <i class="bx bx-dots-vertical-rounded"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt3">
                                        <a class="dropdown-item" href="/admin-bidang">Lihat Lebih Banyak</a>
                                    </div>
                                </div>
                            </div>
                            <span class="fw-semibold d-block mb-1">Bidang</span>
                            <h3 class="card-title mb-2">{{ count($bidang) }} bidang</h3>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 col-md-12 col-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between">
                                <div class="avatar flex-shrink-0">
                                    <div class="avatar-initial rounded bg-label-danger">
                                        <i class='bx bx-task'></i>
                                    </div>
                                </div>
                                <div class="dropdown">
                                    <button class="btn p-0" type="button" id="cardOpt3" data-bs-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false">
                                        <i class="bx bx-dots-vertical-rounded"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt3">
                                        <a class="dropdown-item" href="/admin-program">Lihat Lebih Banyak</a>
                                    </div>
                                </div>
                            </div>
                            <span class="fw-semibold d-block mb-1">Program</span>
                            <h3 class="card-title mb-2">{{ $program }} program</h3>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 col-md-12 col-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between">
                                <div class="avatar flex-shrink-0">
                                    <div class="avatar-initial rounded bg-label-warning">
                                        <i class='bx bx-filter-alt'></i>
                                    </div>
                                </div>
                                <div class="dropdown">
                                    <button class="btn p-0" type="button" id="cardOpt3" data-bs-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false">
                                        <i class="bx bx-dots-vertical-rounded"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt3">
                                        <a class="dropdown-item" href="/admin-kategori">Lihat Lebih Banyak</a>
                                    </div>
                                </div>
                            </div>
                            <span class="fw-semibold d-block mb-1">Kategori</span>
                            <h3 class="card-title mb-2">{{ $kategori }} kategori</h3>
                        </div>
                    </div>
                </div>

                <div class="col-md-12 col-lg-12 order-2 mb-4">
                    <div class="card h-100">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h5 class="card-title m-0 me-2">Bidang</h5>
                        </div>
                        <div class="card-body">
                            <ul class="p-0 m-0">
                                @php
                                    $colors = [
                                        'bg-label-primary',
                                        'bg-label-success',
                                        'bg-label-danger',
                                        'bg-label-warning',
                                        'bg-label-info',
                                    ];
                                    $i = 0;
                                @endphp

                                @foreach ($bidang as $data)
                                    @if ($data->id != 1)
                                        <li class="d-flex mb-4 pb-1">
                                            <div class="avatar flex-shrink-0 me-3">
                                                <div class="avatar-initial rounded {{ $colors[$i % count($colors)] }}">
                                                    <i class='bx bx-buildings'></i>
                                                </div>
                                            </div>
                                            <div
                                                class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                                <div class="me-2">
                                                    <small class="text-muted d-block mb-1">Bidang
                                                        {{ $i++ + 1 }}</small>
                                                    <h6 class="mb-0">{{ $data->nama }}</h6>
                                                </div>
                                                <div class="user-progress d-flex align-items-center gap-1">
                                                    <h6 class="mb-0">{{ count($data->user) }}</h6>
                                                    <span class="text-muted">Pengguna</span>
                                                </div>
                                            </div>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let dateBefore = new Date().getFullYear() - 1;
        let dateNow = new Date().getFullYear();
        var options = {
            series: [{
                    name: dateBefore,
                    data: @json($chartAgenda[date('Y') - 1])
                },
                {
                    name: dateNow,
                    data: @json($chartAgenda[date('Y')])
                }
            ],
            chart: {
                height: 350,
                type: 'area'
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth'
            },
            xaxis: {
                type: 'month',
                categories: ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September",
                    "Oktober", "November", "Desember"
                ]
            },
            tooltip: {
                x: {
                    format: 'dd/MM/yy HH:mm'
                },
            },
        };

        var chart = new ApexCharts(document.querySelector("#totalRevenueChart"), options);
        chart.render();

        var options = {
            series: [{
                    name: dateBefore,
                    data: @json($chartNews[date('Y') - 1])
                },
                {
                    name: dateNow,
                    data: @json($chartNews[date('Y')])
                }
            ],
            chart: {
                height: 350,
                type: 'area'
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth'
            },
            xaxis: {
                type: 'month',
                categories: ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September",
                    "Oktober", "November", "Desember"
                ]
            },
            tooltip: {
                x: {
                    format: 'dd/MM/yy HH:mm'
                },
            },
        };

        var chart = new ApexCharts(document.querySelector("#totalRevenueChartNews"), options);
        chart.render();

        const growthChartEl = document.querySelector('#growthChart'),
            growthChartOptions = {
                series: [@json($agendaGrowth)],
                labels: ['Growth'],
                chart: {
                    height: 240,
                    type: 'radialBar'
                },
                plotOptions: {
                    radialBar: {
                        size: 150,
                        offsetY: 10,
                        startAngle: -150,
                        endAngle: 150,
                        hollow: {
                            size: '55%'
                        },
                        track: {
                            // background: cardColor,
                            strokeWidth: '100%'
                        },
                        dataLabels: {
                            name: {
                                offsetY: 15,
                                // color: headingColor,
                                fontSize: '15px',
                                fontWeight: '600',
                                fontFamily: 'Public Sans'
                            },
                            value: {
                                offsetY: -25,
                                // color: headingColor,
                                fontSize: '22px',
                                fontWeight: '500',
                                fontFamily: 'Public Sans'
                            }
                        }
                    }
                },
                colors: [config.colors.primary],
                fill: {
                    type: 'gradient',
                    gradient: {
                        shade: 'dark',
                        shadeIntensity: 0.5,
                        gradientToColors: [config.colors.primary],
                        inverseColors: true,
                        opacityFrom: 1,
                        opacityTo: 0.6,
                        stops: [30, 70, 100]
                    }
                },
                stroke: {
                    dashArray: 5
                },
                grid: {
                    padding: {
                        top: -35,
                        bottom: -10
                    }
                },
                states: {
                    hover: {
                        filter: {
                            type: 'none'
                        }
                    },
                    active: {
                        filter: {
                            type: 'none'
                        }
                    }
                }
            };
        if (typeof growthChartEl !== undefined && growthChartEl !== null) {
            const growthChart = new ApexCharts(growthChartEl, growthChartOptions);
            growthChart.render();
        }

        const growthChartElNews = document.querySelector('#growthChartNews'),
            growthChartOptionsNews = {
                series: [@json($newsGrowth)],
                labels: ['Growth'],
                chart: {
                    height: 240,
                    type: 'radialBar'
                },
                plotOptions: {
                    radialBar: {
                        size: 150,
                        offsetY: 10,
                        startAngle: -150,
                        endAngle: 150,
                        hollow: {
                            size: '55%'
                        },
                        track: {
                            // background: cardColor,
                            strokeWidth: '100%'
                        },
                        dataLabels: {
                            name: {
                                offsetY: 15,
                                // color: headingColor,
                                fontSize: '15px',
                                fontWeight: '600',
                                fontFamily: 'Public Sans'
                            },
                            value: {
                                offsetY: -25,
                                // color: headingColor,
                                fontSize: '22px',
                                fontWeight: '500',
                                fontFamily: 'Public Sans'
                            }
                        }
                    }
                },
                colors: [config.colors.primary],
                fill: {
                    type: 'gradient',
                    gradient: {
                        shade: 'dark',
                        shadeIntensity: 0.5,
                        gradientToColors: [config.colors.primary],
                        inverseColors: true,
                        opacityFrom: 1,
                        opacityTo: 0.6,
                        stops: [30, 70, 100]
                    }
                },
                stroke: {
                    dashArray: 5
                },
                grid: {
                    padding: {
                        top: -35,
                        bottom: -10
                    }
                },
                states: {
                    hover: {
                        filter: {
                            type: 'none'
                        }
                    },
                    active: {
                        filter: {
                            type: 'none'
                        }
                    }
                }
            };
        if (typeof growthChartElNews !== undefined && growthChartElNews !== null) {
            const growthChart = new ApexCharts(growthChartElNews, growthChartOptionsNews);
            growthChart.render();
        }
    </script>
@endsection
