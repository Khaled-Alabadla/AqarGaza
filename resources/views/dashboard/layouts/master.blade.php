<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="Description" content="Bootstrap Responsive Admin Web Dashboard HTML5 Template">
        <meta name="Author" content="Spruko Technologies Private Limited">
        <meta name="Keywords"
            content="admin,admin dashboard,admin dashboard template,admin panel template,admin template,admin theme,bootstrap 4 admin template,bootstrap 4 dashboard,bootstrap admin,bootstrap admin dashboard,bootstrap admin panel,bootstrap admin template,bootstrap admin theme,bootstrap dashboard,bootstrap form template,bootstrap panel,bootstrap ui kit,dashboard bootstrap 4,dashboard design,dashboard html,dashboard template,dashboard ui kit,envato templates,flat ui,html,html and css templates,html dashboard template,html5,jquery html,premium,premium quality,sidebar bootstrap 4,template admin bootstrap 4" />
        <link rel="icon" href="{{ asset('assets/img/logo.jpg') }}">

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link
            href="https://fonts.googleapis.com/css2?family=Cairo:wght@200..1000&family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap"
            rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

        <style>
            body {
                font-family: "Cairo", sans-serif !important;

            }

            .app .bg-donors-gradient {
                background: linear-gradient(135deg, #f39c12, #d35400);
                /* Example colors: orange to red */
            }

            thead tr th,
            tbody tr td {
                display: table-cell !important
            }

            table#example {
                width: 100% !important;
                margin: 0
            }

            .alert {
                /* visibility: visible; */
                transition: all 0.5s ease;
                /* margin: 0 !important */
            }

            @media(only screen and (max-width: 991px)) {
                .desktop-logo {
                    margin-right: auto !important;
                }
            }

            .card-header.bg-transparent {
                display: flex !important;
                justify-content: center !important;
                align-items: center !important
            }





            .buttons-html5,
            .buttons-colvis {
                margin-left: 10px !important;
                margin-bottom: 10px;
                border-radius: 10px !important
            }

            .buttons-colvis,
            .dt-button-collection.dropdown-menu .dropdown-item.active,
            .dt-button-collection.dropdown-menu .dropdown-item:active {
                background: #333 !important
            }

            .buttons-pdf,
            .buttons-copy {
                display: none !important
            }

            .buttons-excel {
                background: #217346 !important
            }

            .fa.fa-cog {
                font-size: 21px;
                margin-left: 14px;
                color: gray;
            }

            .fa-question,
            .fa-hands-helping,
            .fa-lock,
            .fa-gift {
                font-size: 17px;
                margin-left: 14px;
                color: gray;
            }

            .app-sidebar svg,
            .app-sidebar i {
                margin-left: 14px !important
            }
        </style>
        @include('dashboard.layouts.head')
    </head>

    <body class="main-body app sidebar-mini">
        <!-- Loader -->
        <div id="global-loader">
            <img src="{{ URL::asset('assets/img/loader.svg') }}" class="loader-img" alt="Loader">
        </div>
        <!-- /Loader -->
        @include('dashboard.layouts.main-sidebar')
        <!-- main-content -->
        <div class="main-content app-content">
            @include('dashboard.layouts.main-header')
            <!-- container -->
            <div class="container-fluid">
                @yield('page-header')
                @yield('content')
                @include('dashboard.layouts.sidebar')
                @include('dashboard.layouts.models')
                @include('dashboard.layouts.footer')
                @include('dashboard.layouts.footer-scripts')
    </body>

    {{-- <script>
		let section = document.querySelector('.breadcrumb-header');

		let footer = document.querySelector('.main-footer');

		footer.style.width = section.width

	</script> --}}

    <script>
        // Function to hide alert after 5 seconds
        function hideAlert(id) {
            setTimeout(function() {
                let alertElement = document.getElementById(id);
                if (alertElement) {
                    // alertElement.style.visibility='hidden';
                    alertElement.style.opacity = 0;
                    alertElement.style.maxHeight = 0;
                    alertElement.style.padding = 0;
                    alertElement.style.marginBottom = 0
                }
            }, 5000);
        }

        @if (session('success'))
            hideAlert('success-alert');
        @endif
    </script>

    <script>
        // Function to update footer position based on sidebar width
        function updateFooterPosition() {
            let sidebar = document.querySelector('.app-sidebar');
            let footer = document.querySelector('.main-footer');

            if (!sidebar || !footer) return; // Prevent errors if elements are not found

            // Get the computed width of the sidebar
            let sidebarWidth = window.getComputedStyle(sidebar).width;

            // Convert sidebarWidth to integer for calculation (remove 'px' part)
            sidebarWidth = parseFloat(sidebarWidth);

            // Ensure footer is positioned absolutely relative to the body or its nearest positioned ancestor
            footer.style.position = 'absolute';
            footer.style.right = sidebarWidth + 'px'; // Align right of footer with the width of sidebar
            footer.style.bottom = '0'; // Ensure it's at the bottom of the screen
            footer.style.width = `calc(100% - ${sidebarWidth}px)`; // Adjust width to take up the remaining space
        }

        window.onload = () => {
            // Initial footer position update when the page loads
            updateFooterPosition();

            // Use setInterval to automatically update the footer position every 1 second
            setInterval(updateFooterPosition, 10); // 1000ms = 1 second
        };
    </script>

</html>
