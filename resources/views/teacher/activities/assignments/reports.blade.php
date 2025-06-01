@extends('layouts.teacher.master')

@section('page-css')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/apex-charts/apex-charts.css') }}" />
@endsection

@section('title', pageTitle(trans('main.reportsOf', ['dependency' => trans('admin/assignments.assignments')])))

@section('content')
    <!-- Submission Trends -->
    <div class="row mb-6">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-md-center align-items-start">
                    <h5 class="card-title mb-0">{{ trans('admin/assignments.submissionTrends') }}</h5>
                </div>
                <div class="card-body">
                    <div id="submissionTrendsChart"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Student Stats & Score Distribution -->
    <div class="row g-6 mb-6 align-items-stretch">
        <!-- Student Stats -->
        <div class="col-md-6">
            <div class="d-flex flex-column h-100">
                <div class="row mt-auto">
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center flex-wrap">
                                    <div class="avatar me-4">
                                        <div class="avatar-initial bg-label-primary rounded-3">
                                            <i class="icon-base ri ri-trophy-line icon-24px"> </i>
                                        </div>
                                    </div>
                                    <div class="card-info">
                                        <div class="d-flex align-items-center">
                                            <h5 class="mb-0 me-2">{{ $data['averageScore'] }}</h5>
                                        </div>
                                        <p class="mb-0">{{ trans('admin/quizzes.averageScore') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center flex-wrap">
                                    <div class="avatar me-4">
                                        <div class="avatar-initial bg-label-primary rounded-3">
                                            <i class="icon-base ri ri-file-line icon-24px"> </i>
                                        </div>
                                    </div>
                                    <div class="card-info">
                                        <div class="d-flex align-items-center">
                                            <h5 class="mb-0 me-2">{{ $data['avgFiles'] }}</h5>
                                        </div>
                                        <p class="mb-0">{{ trans('admin/assignments.avgFiles') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center flex-wrap">
                                    <div class="avatar me-4">
                                        <div class="avatar-initial bg-label-primary rounded-3">
                                            <i class="icon-base ri ri-database-line icon-24px"> </i>
                                        </div>
                                    </div>
                                    <div class="card-info">
                                        <div class="d-flex align-items-center">
                                            <h5 class="mb-0 me-2">{{ $data['avgFileSize'] }} MB</h5>
                                        </div>
                                        <p class="mb-0">{{ trans('admin/assignments.avgFileSize') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card h-100">
                    <div class="card-header">
                        <div class="d-flex justify-content-between flex-wrap gap-2">
                            <p class="d-block mb-0 text-body">{{ trans('admin/quizzes.totalStudents') }}</p>
                        </div>
                        <h4 class="mb-0">{{ $data['totalStudents'] }}</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-4">
                                <div class="d-flex gap-2 align-items-center mb-2">
                                    <div class="avatar avatar-xs flex-shrink-0">
                                        <div class="avatar-initial rounded bg-label-primary">
                                            <i class="icon-base ri ri-checkbox-circle-line icon-22px"></i>
                                        </div>
                                    </div>
                                    <p class="mb-0">{{ trans('admin/assignments.tookAssignment') }}</p>
                                </div>
                                <h4 class="mb-2">{{ $data['tookAssignmentPercentage'] }}%</h4>
                                <p class="mb-0">{{ $data['tookAssignment'] }}</p>
                            </div>
                            <div class="col-4">
                                <div class="divider divider-vertical">
                                    <div class="divider-text">
                                        <span class="badge-divider-bg bg-label-secondary p-2">VS</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="d-flex gap-2 justify-content-end align-items-center mb-2">
                                    <p class="mb-0">{{ trans('admin/assignments.havenotTakenAssignment') }}</p>
                                    <div class="avatar avatar-xs flex-shrink-0">
                                        <div class="avatar-initial rounded bg-label-warning">
                                            <i class="icon-base ri ri-close-circle-line icon-22px"></i>
                                        </div>
                                    </div>
                                </div>
                                <h4 class="mb-2">{{ $data['havenotTakenAssignmentPercentage'] }}%</h4>
                                <p class="mb-0">{{ $data['havenotTakenAssignment'] }}</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mt-4">
                            <div class="progress w-100 rounded" style="height: 8px;">
                                <div class="progress-bar bg-primary"
                                    style="width: {{ $data['tookAssignmentPercentage'] }}%" role="progressbar"
                                    aria-valuenow="{{ $data['tookAssignmentPercentage'] }}" aria-valuemin="0"
                                    aria-valuemax="100"></div>
                                <div class="progress-bar bg-warning" role="progressbar"
                                    style="width: {{ $data['havenotTakenAssignmentPercentage'] }}%"
                                    aria-valuenow="{{ $data['havenotTakenAssignmentPercentage'] }}" aria-valuemin="0"
                                    aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Score Distribution -->
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-0 mb-1">{{ trans('admin/quizzes.scoreDistribution') }}</h5>
                    </div>
                </div>
                <div class="card-body">
                    <div id="scoreDistributionChart"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Students Who took Datatable & Top Students -->
    <div class="row g-6 mb-6">
        <!-- Students Who took Datatable -->
        <div class="col-md-8">
            <x-datatable id="students-took-assignment-datatable"
                datatableTitle="{{ trans('admin/assignments.studentsTookAssignment') }}">
                <th></th>
                <th>{{ trans('admin/quizzes.rank') }}</th>
                <th>{{ trans('main.name') }}</th>
                <th>{{ trans('main.score') }}</th>
                <th>{{ trans('main.link') }}</th>
            </x-datatable>
        </div>
        <!-- Top Students -->
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <div class="card-title mb-0">
                        <h5 class="m-0 me-2">{{ trans('admin/quizzes.top10Students') }}</h5>
                    </div>
                </div>
                <div class="px-5 py-4 border border-start-0 border-end-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-heading text-uppercase">{{ trans('admin/students.students') }}</small>
                        <small class="text-heading text-uppercase">{{ trans('main.score') }}</small>
                    </div>
                </div>
                <div class="card-body pt-5">
                    @forelse ($data['topStudents'] as $student)
                        <div class="d-flex justify-content-between align-items-center mb-6">
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar me-4">
                                    <img src="{{ $student['profile_pic'] ? asset('storage/profiles/students/' . $student['profile_pic']) : asset('assets/img/avatars/default.jpg') }}"
                                        alt="avatar" class="rounded-circle">
                                </div>
                                <div>
                                    <div>
                                        <a target="_blank" href="#" class="h6 text-truncate">
                                            <p class="mb-0 fw-medium">{{ $student['name'] }}</p>
                                        </a>
                                        <small class="text-truncate">{{ $student['phone'] }}</small>
                                    </div>
                                </div>
                            </div>
                            <div class="text-end">
                                <h6 class="mb-0">{{ $student['assignment_score'] }}</h6>
                            </div>
                        </div>
                    @empty
                        <div class="text-center">{{ trans('main.datatable.empty_table') }}</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
    <!-- Students Who Didn't take Datatable -->
    <div class="row mb-6">
        <div class="col-md-12">
            <x-datatable id="students-havenot-taken-assignment-datatable"
                datatableTitle="{{ trans('admin/assignments.studentsHavenotTakenAssignment') }}">
                <th></th>
                <th>{{ trans('main.name') }}</th>
            </x-datatable>
        </div>
    </div>
@endsection

@section('page-js')
    <script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const chartColors = {
                area: {
                    series1: '#ab7efd',
                    series2: '#b992fe',
                    series3: '#e0cffe'
                },
            };
            let borderColor, labelColor, headingColor, legendColor;

            if (isDarkStyle) {
                borderColor = config.colors_dark.borderColor;
                labelColor = config.colors_dark.textMuted;
                headingColor = config.colors_dark.headingColor;
                legendColor = config.colors_dark.bodyColor;
            } else {
                borderColor = config.colors.borderColor;
                labelColor = config.colors.textMuted;
                headingColor = config.colors.headingColor;
                legendColor = config.colors.bodyColor;
            }

            initializeDataTable('#students-took-assignment-datatable',
                "{{ route('teacher.assignments.studentsTookAssignment', $assignment->uuid) }}", [1, 2, 3, 4],
                [{
                        data: "",
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'rank',
                        name: 'rank',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'details',
                        name: 'details'
                    },
                    {
                        data: 'score',
                        name: 'score',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'link',
                        name: 'link',
                        orderable: false,
                        searchable: false
                    },
                ],
            );
            initializeDataTable('#students-havenot-taken-assignment-datatable',
                "{{ route('teacher.assignments.studentsHavenotTakenAssignment', $assignment->uuid) }}", [0, 1],
                [{
                        data: "",
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'details',
                        name: 'details'
                    },
                ],
            );

            const scoreDistributionChartE = document.querySelector('#scoreDistributionChart'),
                scoreDistributionChartConfig = {
                    chart: {
                        fontFamily: 'Alexandria',
                        type: 'bar',
                        toolbar: {
                            show: false
                        }
                    },
                    plotOptions: {
                        bar: {
                            horizontal: true,
                            barHeight: '30%',
                            startingShape: 'rounded',
                            borderRadius: 8
                        }
                    },
                    grid: {
                        borderColor: borderColor,
                        xaxis: {
                            lines: {
                                show: false
                            }
                        },
                        padding: {
                            top: -20,
                            bottom: -12
                        }
                    },
                    colors: config.colors.info,
                    series: [{
                        name: '{{ trans('account.studentsCount') }}',
                        data: Object.values(@json($data['scoreDistribution']))
                    }],
                    xaxis: {
                        categories: @json($data['scoreRanges']),
                        axisBorder: {
                            show: false
                        },
                        axisTicks: {
                            show: false
                        },
                        labels: {
                            style: {
                                colors: labelColor,
                                fontSize: '13px'
                            }
                        },
                        max: {{ max($data['scoreDistribution']) + 2 }},
                        tickAmount: {{ max(1, max($data['scoreDistribution']) + 2) }},
                        forceNiceScale: false,
                    },
                    yaxis: {
                        labels: {
                            style: {
                                colors: labelColor,
                                fontSize: '13px'
                            },
                        }
                    },
                    tooltip: {
                        y: {
                            formatter: (val) => `${val}`
                        }
                    }
                };
            const submissionTrendsChartE = document.querySelector('#submissionTrendsChart'),
                submissionTrendsData = @json($data['submissionTrends']),
                maxSubmissions = Math.max(...submissionTrendsData, 1),
                yAxisMax = Math.max(5, Math.ceil(maxSubmissions * 1.2)),
                submissionTrendsChartConfig = {
                    chart: {
                        height: 400,
                        fontFamily: 'Alexandria',
                        type: 'area',
                        toolbar: {
                            show: false
                        }
                    },
                    dataLabels: {
                        enabled: false
                    },
                    stroke: {
                        show: true,
                        curve: 'smooth',
                        width: 2
                    },
                    legend: {
                        show: false,
                    },
                    grid: {
                        borderColor: borderColor,
                        xaxis: {
                            lines: {
                                show: true
                            }
                        }
                    },
                    colors: [chartColors.area.series1],
                    series: [{
                        name: '{{ trans('admin/assignments.submissionsCount') }}',
                        data: @json($data['submissionTrends'])
                    }],
                    xaxis: {
                        categories: @json($data['submissionDates']),
                        axisBorder: {
                            show: false
                        },
                        axisTicks: {
                            show: false
                        },
                        labels: {
                            style: {
                                colors: labelColor,
                                fontSize: '13px'
                            },
                        },
                        tickAmount: 14,
                        tickPlacement: 'on'
                    },
                    yaxis: {
                        labels: {
                            style: {
                                colors: labelColor,
                                fontSize: '13px'
                            },
                        },
                        min: 0,
                        max: yAxisMax,
                        tickAmount: 5,
                        forceNiceScale: false
                    },
                    fill: {
                        opacity: 1,
                        type: 'solid'
                    },
                    tooltip: {
                        y: {
                            formatter: (val) => `${val}`
                        },
                        x: {
                            formatter: (val, {
                                dataPointIndex
                            }) => @json($data['submissionDates'])[dataPointIndex] || ''
                        }
                    },
                };
            if (typeof scoreDistributionChartE !== undefined && scoreDistributionChartE !== null) {
                const scoreDistributionChart = new ApexCharts(scoreDistributionChartE,
                    scoreDistributionChartConfig);
                scoreDistributionChart.render();
            }
            if (typeof submissionTrendsChartE !== undefined && submissionTrendsChartE !== null) {
                const submissionTrendsChart = new ApexCharts(submissionTrendsChartE, submissionTrendsChartConfig);
                submissionTrendsChart.render();
            }
        });
    </script>
@endsection
