<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>มหกรรมวิชาการ YALA EDU-FEST 2025</title>
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
    <style>
        .school-blue {
            color: #007bff;
            font-weight: bold;
        }

        [Responsive for intro-wrapper section] .intro-wrapper {
            padding: 3rem 0;
        }

        .intro-wrapper .intro {
            max-width: 900px;
            margin: 0 auto;
            padding: 2rem;
        }

        .intro-wrapper .text-align-center {
            text-align: center;
        }

        .intro-wrapper iframe {
            border-radius: 16px;
            max-width: 100%;
            height: 500px;
        }

        @media (max-width: 992px) {
            .intro-wrapper .intro {
                padding: 1.5rem;
            }

            .intro-wrapper iframe {
                height: 350px;
            }
        }

        @media (max-width: 768px) {
            .intro-wrapper {
                padding: 1.5rem 0;
            }

            .intro-wrapper .intro {
                padding: 1rem;
            }

            .intro-wrapper iframe {
                height: 220px;
            }

            .intro-wrapper h2.heading-style-h3 {
                font-size: 1.3rem;
            }

            .intro-wrapper p.text-size-medium {
                font-size: 1rem;
            }
        }

        @media (max-width: 480px) {
            .intro-wrapper .intro {
                padding: 0.5rem;
            }

            .intro-wrapper iframe {
                height: 150px;
            }
        }

        :root {
            --primary-green: #00660c;
            --secondary-green: #139923;
            --light-green: #009011;
            --accent-gold: #FFD700;
            --light-gold: #FFF8DC;
            --dark-green: #053d0b;

            --primary-gradient: linear-gradient(135deg, var(--primary-green) 0%, var(--secondary-green) 70%, var(--accent-gold) 100%);
            --hero-gradient: linear-gradient(135deg, var(--dark-green) 0%, var(--primary-green) 40%, var(--secondary-green) 70%, var(--accent-gold) 100%);
            --blue-gradient: linear-gradient(135deg, var(--light-green) 0%, var(--primary-green) 100%);
            --gold-gradient: linear-gradient(135deg, var(--accent-gold) 0%, #FFC107 100%);
            --glass-bg: rgba(21, 101, 192, 0.1);
            --glass-border: rgba(255, 215, 0, 0.3);
            --shadow-light: 0 8px 32px rgba(21, 101, 192, 0.2);
            --shadow-heavy: 0 20px 60px rgba(13, 71, 161, 0.3);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            overflow-x: hidden;
            line-height: 1.6;
            background: #f8f9fa;
            /* Hide scroll bar for all browsers */
            scrollbar-width: none;
            /* Firefox */
            -ms-overflow-style: none;
            /* IE and Edge */
        }

        body::-webkit-scrollbar,
        html::-webkit-scrollbar {
            display: none;
            /* Chrome, Safari, Opera */
        }

        /* Hero Section - Full Screen */
        .hero-section {
            height: 100vh;
            background: var(--hero-gradient);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background:
                radial-gradient(circle at 20% 20%, rgba(255, 215, 0, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(21, 101, 192, 0.15) 0%, transparent 50%);
            pointer-events: none;
        }

        .floating-elements {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
        }

        .floating-circle {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 215, 0, 0.1);
            opacity: 0;
            animation: float 8s ease-in-out infinite, circleEntrance 2s ease-out forwards;
        }

        .floating-circle:nth-child(1) {
            width: 100px;
            height: 100px;
            top: 15%;
            left: 10%;
            animation-delay: 1s, 0.5s;
            background: rgba(21, 101, 192, 0.1);
        }

        .floating-circle:nth-child(2) {
            width: 150px;
            height: 150px;
            top: 10%;
            right: 15%;
            animation-delay: 3s, 1s;
            background: rgba(255, 215, 0, 0.1);
        }

        .floating-circle:nth-child(3) {
            width: 80px;
            height: 80px;
            bottom: 20%;
            left: 20%;
            animation-delay: 6s, 1.5s;
            background: rgba(21, 101, 192, 0.1);
        }

        @keyframes circleEntrance {
            0% {
                opacity: 0;
                transform: scale(0) rotate(180deg);
            }

            100% {
                opacity: 0.4;
                transform: scale(1) rotate(0deg);
            }
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px) rotate(0deg);
                opacity: 0.4;
            }

            50% {
                transform: translateY(-30px) rotate(180deg);
                opacity: 0.8;
            }
        }

        /* Animated Particles */
        .particles {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
        }

        .particle {
            position: absolute;
            width: 4px;
            height: 4px;
            background: var(--accent-gold);
            border-radius: 50%;
            opacity: 0;
            animation: particleFloat 15s linear infinite;
        }

        .particle:nth-child(1) {
            left: 10%;
            animation-delay: 0s;
        }

        .particle:nth-child(2) {
            left: 20%;
            animation-delay: 2s;
        }

        .particle:nth-child(3) {
            left: 30%;
            animation-delay: 4s;
        }

        .particle:nth-child(4) {
            left: 40%;
            animation-delay: 6s;
        }

        .particle:nth-child(5) {
            left: 60%;
            animation-delay: 8s;
        }

        .particle:nth-child(6) {
            left: 70%;
            animation-delay: 10s;
        }

        .particle:nth-child(7) {
            left: 80%;
            animation-delay: 12s;
        }

        .particle:nth-child(8) {
            left: 90%;
            animation-delay: 14s;
        }

        @keyframes particleFloat {
            0% {
                opacity: 0;
                transform: translateY(100vh) scale(0);
            }

            10% {
                opacity: 1;
                transform: translateY(90vh) scale(1);
            }

            90% {
                opacity: 1;
                transform: translateY(10vh) scale(1);
            }

            100% {
                opacity: 0;
                transform: translateY(0vh) scale(0);
            }
        }

        /* Logo and Title */
        .hero-content {
            text-align: center;
            z-index: 10;
            position: relative;
            opacity: 0;
            animation: landingPageEntrance 3s ease-out forwards;
        }

        .logo-container {
            margin-bottom: 1rem;
            opacity: 1;
            transform: scale(1) rotate(0deg);
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
        }

        .logo {
            width: 250px;
            height: 200px;
            background: transparent;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            box-shadow: none;
            border: none;
            position: relative;
            overflow: hidden;
            padding: 0rem;
        }

        .logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            position: relative;
            z-index: 2;
            filter: drop-shadow(0 5px 30px rgba(0, 0, 0, 0.3)) drop-shadow(0 0 40px rgba(255, 215, 0, 0.5));
        }

        .festival-title {
            font-size: clamp(3rem, 8vw, 8rem);
            font-weight: 900;
            background: linear-gradient(45deg, #ffffff 0%, var(--accent-gold) 50%, #ffffff 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 0.5rem;
            letter-spacing: -0.05em;
            text-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            opacity: 0;
            transform: translateY(100px) scale(0.8);
            animation: titleEntrance 2s ease-out 1s forwards, titleGlow 3s ease-in-out 3.5s infinite alternate;
        }

        @keyframes titleGlow {
            from {
                filter: drop-shadow(0 0 20px rgba(255, 215, 0, 0.6));
            }

            to {
                filter: drop-shadow(0 0 40px rgba(255, 215, 0, 0.9));
            }
        }

        .festival-subtitle {
            font-size: clamp(1.2rem, 3vw, 2rem);
            color: rgba(255, 255, 255, 0.95);
            margin-bottom: 0.5rem;
            font-weight: 600;
            text-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            opacity: 0;
            transform: translateY(50px);
            animation: subtitleEntrance 1.5s ease-out 1.8s forwards;
        }

        .festival-subtitle:nth-of-type(3) {
            animation-delay: 2.2s;
        }

        /* Landing Page Entrance Animations */
        @keyframes landingPageEntrance {
            0% {
                opacity: 0;
                transform: perspective(1000px) rotateX(90deg);
            }

            100% {
                opacity: 1;
                transform: perspective(1000px) rotateX(0deg);
            }
        }

        @keyframes titleEntrance {
            0% {
                opacity: 0;
                transform: translateY(100px) scale(0.8);
                filter: blur(10px);
            }

            60% {
                opacity: 0.8;
                transform: translateY(-20px) scale(1.05);
                filter: blur(2px);
            }

            100% {
                opacity: 1;
                transform: translateY(0) scale(1);
                filter: blur(0px);
            }
        }

        @keyframes subtitleEntrance {
            0% {
                opacity: 0;
                transform: translateY(50px);
                filter: blur(5px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
                filter: blur(0px);
            }
        }

        /* Countdown in Hero */
        .hero-countdown {
            margin-top: 1.5rem;
            opacity: 0;
            transform: translateY(80px);
            animation: countdownEntrance 2s ease-out 2.5s forwards;
        }

        @keyframes countdownEntrance {
            0% {
                opacity: 0;
                transform: translateY(80px) scale(0.8);
            }

            100% {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .countdown-timer {
            display: flex;
            justify-content: center;
            gap: 2rem;
            flex-wrap: wrap;
        }

        .countdown-item {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border: 2px solid var(--glass-border);
            border-radius: 20px;
            padding: 1.5rem;
            min-width: 100px;
            text-align: center;
            transition: all 0.3s ease;
            opacity: 0;
            transform: scale(0.5) rotateY(180deg);
            animation: countdownItemEntrance 1s ease-out forwards;
        }

        .countdown-item:nth-child(1) {
            animation-delay: 3s;
        }

        .countdown-item:nth-child(2) {
            animation-delay: 3.2s;
        }

        .countdown-item:nth-child(3) {
            animation-delay: 3.4s;
        }

        .countdown-item:nth-child(4) {
            animation-delay: 3.6s;
        }

        @keyframes countdownItemEntrance {
            0% {
                opacity: 0;
                transform: scale(0.5) rotateY(180deg);
            }

            60% {
                opacity: 1;
                transform: scale(1.1) rotateY(10deg);
            }

            100% {
                opacity: 1;
                transform: scale(1) rotateY(0deg);
            }
        }

        .countdown-item:hover {
            transform: translateY(-5px);
            border-color: var(--accent-gold);
            background: rgba(255, 215, 0, 0.1);
        }

        .countdown-number {
            display: block;
            font-size: 3rem;
            font-weight: 900;
            background: var(--gold-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            line-height: 1;
        }

        .countdown-label {
            font-size: 1rem;
            color: rgba(255, 255, 255, 0.9);
            margin-top: 0.5rem;
            font-weight: 600;
        }

        /* Scroll Indicator */
        .scroll-indicator {
            position: absolute;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
            color: var(--accent-gold);
            font-size: 2rem;
            cursor: pointer;
            opacity: 0;
            animation: scrollIndicatorEntrance 1s ease-out 4s forwards, bounce 2s infinite 5s;
        }

        @keyframes scrollIndicatorEntrance {
            0% {
                opacity: 0;
                transform: translateX(-50%) translateY(50px);
            }

            100% {
                opacity: 1;
                transform: translateX(-50%) translateY(0);
            }
        }

        @keyframes bounce {

            0%,
            20%,
            50%,
            80%,
            100% {
                transform: translateX(-50%) translateY(0);
            }

            40% {
                transform: translateX(-50%) translateY(-10px);
            }

            60% {
                transform: translateX(-50%) translateY(-5px);
            }
        }

        /* Sticky Navigation */
        .sticky-navigation {
            position: fixed;
            left: 30px;
            top: 50%;
            transform: translateY(-50%);
            z-index: 999;
            background: rgba(33, 117, 12, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 25px;
            padding: 1rem;
            border: 2px solid var(--glass-border);
            opacity: 0;
            transition: all 0.3s ease;
            animation: stickyNavEntrance 1s ease-out 5.5s forwards;
        }

        @keyframes stickyNavEntrance {
            0% {
                opacity: 0;
                transform: translateY(-50%) translateX(-100px);
            }

            100% {
                opacity: 1;
                transform: translateY(-50%) translateX(0);
            }
        }

        .sticky-nav-item {
            display: block;
            width: 50px;
            height: 50px;
            margin: 10px 0;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            text-decoration: none;
            color: rgba(255, 255, 255, 0.7);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        /* Responsive for Hero Content */
        @media (max-width: 1024px) {
            .hero-content {
                padding: 2rem 1rem;
            }

            .logo-container {
                width: 100%;
                max-width: 100vw;
                display: flex;
                justify-content: center;
                align-items: center;
            }
        }

        @media (max-width: 768px) {
            .hero-content {
                padding: 1.2rem 0.5rem;
            }

            .logo-container {
                width: 100%;
                max-width: 100vw;
                margin-bottom: 1rem;
                display: flex;
                justify-content: center;
                align-items: center;
            }

            .logo {
                height: 120px;
            }

            .festival-title {
                font-size: 2.5rem !important;
                margin-bottom: 0.3rem;
            }

            .festival-subtitle {
                font-size: 1rem !important;
                margin-bottom: 0.15rem;
            }

            .hero-countdown {
                margin-top: 1rem;
                margin-bottom: 0.7rem;
            }

            .countdown-timer {
                gap: 0.7rem;
            }

            .countdown-item {
                min-width: 70px;
                padding: 0.7rem;
            }

            .countdown-number {
                font-size: 2.0rem !important;
            }

            .countdown-label {
                font-size: 0.85rem !important;
            }
        }

        @media (max-width: 480px) {
            .hero-content {
                padding: 0.7rem 0.2rem;
            }

            .logo-container {
                width: 100%;
                max-width: 100vw;
                margin-bottom: 0.5rem;
                display: flex;
                justify-content: center;
                align-items: center;
            }

            .logo {
                height: 70px;
            }

            .festival-title {
                font-size: 2rem !important;
            }

            .festival-subtitle {
                font-size: 0.7rem !important;
            }

            .countdown-number {
                font-size: 2rem !important;
            }

            .countdown-label {
                font-size: 0.6rem !important;
            }

            .countdown-item {
                min-width: 45px;
                padding: 0.4rem;
            }
        }

        .sticky-nav-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: var(--gold-gradient);
            transition: all 0.3s ease;
            z-index: -1;
        }

        .sticky-nav-item:hover,
        .sticky-nav-item.active {
            color: var(--primary-green);
            background: rgba(255, 215, 0, 0.9);
            transform: scale(1.1);
            box-shadow: 0 10px 20px rgba(255, 215, 0, 0.3);
        }

        .sticky-nav-item:hover::before,
        .sticky-nav-item.active::before {
            left: 0;
        }

        .sticky-nav-tooltip {
            position: absolute;
            left: 70px;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(40, 161, 13, 0.95);
            color: white;
            padding: 8px 15px;
            border-radius: 10px;
            font-size: 0.9rem;
            font-weight: 600;
            white-space: nowrap;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            pointer-events: none;
        }

        .sticky-nav-tooltip::before {
            content: '';
            position: absolute;
            right: 100%;
            top: 50%;
            transform: translateY(-50%);
            border: 8px solid transparent;
            border-right-color: rgba(13, 161, 25, 0.95);
        }

        .sticky-nav-item:hover .sticky-nav-tooltip {
            opacity: 1;
            visibility: visible;
            left: 65px;
        }

        /* Back Button */
        .back-btn {
            position: fixed;
            top: 30px;
            left: 30px;
            background: rgba(38, 141, 12, 0.9);
            backdrop-filter: blur(20px);
            border: 2px solid var(--glass-border);
            padding: 15px 25px;
            border-radius: 50px;
            color: white;
            text-decoration: none;
            font-weight: 600;
            z-index: 1000;
            transition: all 0.3s ease;
            opacity: 0;
            transform: translateX(-100px);
            animation: backBtnEntrance 1s ease-out 4.5s forwards;
            font-size: 1rem;
        }

        @keyframes backBtnEntrance {
            0% {
                opacity: 0;
                transform: translateX(-100px);
            }

            100% {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .back-btn:hover {
            background: var(--accent-gold);
            color: var(--primary-green);
            transform: translateY(-3px);
            border-color: var(--accent-gold);
        }

        /* Page Loader */
        .page-loader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: var(--hero-gradient);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            animation: pageLoaderFadeOut 1s ease-out 4.8s forwards;
        }

        .loader-content {
            text-align: center;
            color: white;
        }

        .loader-logo {
            width: 80px;
            height: 80px;
            background: var(--gold-gradient);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            animation: loaderSpin 2s ease-in-out infinite;
        }

        .loader-text {
            font-size: 1.2rem;
            font-weight: 600;
            animation: loaderPulse 2s ease-in-out infinite;
        }

        @keyframes pageLoaderFadeOut {
            0% {
                opacity: 1;
                visibility: visible;
            }

            100% {
                opacity: 0;
                visibility: hidden;
            }
        }

        @keyframes loaderSpin {
            0% {
                transform: rotate(0deg) scale(1);
            }

            50% {
                transform: rotate(180deg) scale(1.1);
            }

            100% {
                transform: rotate(360deg) scale(1);
            }
        }

        @keyframes loaderPulse {

            0%,
            100% {
                opacity: 0.6;
            }

            50% {
                opacity: 1;
            }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .countdown-timer {
                gap: 1rem;
            }

            .countdown-item {
                min-width: 80px;
                padding: 1rem;
            }

            .countdown-number {
                font-size: 1.5rem;
            }

            .back-btn {
                top: 15px;
                left: 10px;
                padding: 8px 14px;
                font-size: 0.85rem;
            }

            .floating-circle {
                display: none;
            }

            .sticky-navigation {
                left: 10px;
                transform: translateY(-50%) scale(0.8);
            }

            .sticky-nav-item {
                width: 40px;
                height: 40px;
                margin: 8px 0;
                font-size: 1rem;
            }

            .sticky-nav-tooltip {
                display: none;
            }
        }

        @media (max-width: 480px) {
            .sticky-navigation {
                display: none;
            }

            .back-btn {
                top: 8px;
                left: 5px;
                padding: 6px 10px;
                font-size: 0.75rem;
            }
        }

        /* Sticky Tabs Section Styles */
        .intro-wrapper {
            position: relative;
            height: 90vh;
            background: linear-gradient(135deg, var(--light-gold) 0%, #fffde7 100%);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .intro {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
            position: relative;
        }

        /* About Section Specific Styles */
        .text-align-center {
            text-align: center;
        }

        .max-width-big {
            max-width: 900px;
            width: 100%;
        }

        .align-center {
            margin-left: auto;
            margin-right: auto;
        }

        .margin-bottom {
            margin-bottom: 2rem;
        }

        .margin-small {
            margin-bottom: 1.5rem;
        }

        .heading-style-h3 {
            font-size: 3rem;
            font-weight: 700;
            line-height: 1.2;
            margin: 0;
            color: var(--primary-green);
        }

        .text-size-medium {
            font-size: 1.5rem;
            line-height: 1.8;
            color: #444;
            font-weight: 400;
        }

        /* Responsive for About Section */
        @media (max-width: 768px) {
            .heading-style-h3 {
                font-size: 2.5rem;
            }

            .text-size-medium {
                font-size: 1.3rem;
                line-height: 1.7;
            }

            .max-width-big {
                max-width: 90%;
            }
        }

        @media (max-width: 480px) {
            .heading-style-h3 {
                font-size: 2rem;
            }

            .text-size-medium {
                font-size: 1.2rem;
                line-height: 1.6;
            }
        }

        /* Sticky Tabs Section Styles */
        .intro-wrapper {
            position: relative;
            height: 90vh;
            background: linear-gradient(135deg, var(--light-gold) 0%, #fffde7 100%);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .intro {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
            position: relative;
        }

        .section_tabs {
            z-index: 99;
            border-radius: var(--border-radius--xlarge);
            background-color: var(--dark-green);
            position: relative;
        }

        .padding-section-large {
            padding-top: 7rem;
            padding-bottom: 7rem;
            position: relative;
        }

        .tabs_height {
            height: 600vh;
        }

        .tabs_sticky-wrapper {
            height: 100vh;
            position: sticky;
            top: 5vh;
        }

        .tabs_container {
            width: 100%;
            max-width: 120rem;
            margin-left: auto;
            margin-right: auto;
        }

        .tabs_component {
            height: 90vh;
            grid-column-gap: 1.5rem;
            grid-row-gap: 1.5rem;
            grid-template-rows: auto;
            grid-template-columns: 0.4fr 1fr;
            grid-auto-columns: 1fr;
            padding-left: 3.3%;
            padding-right: 3.3%;
            display: grid;
        }

        .tabs_left {
            border-radius: 20px;
            background-color: var(--primary-green);
            flex-direction: column;
            justify-content: flex-end;
            align-items: stretch;
            padding: 1.5rem;
            display: flex;
            font-size: 1.1rem;
        }

        @media (max-width: 768px) {
            .tabs_left {
                font-size: 0.95rem;
            }

            .tabs_left .heading-style-h4 {
                font-size: 1.1rem;
            }

            .tabs_left .text-size-small {
                font-size: 0.92rem;
            }
        }

        @media (max-width: 480px) {
            .tabs_left {
                font-size: 0.85rem;
            }

            .tabs_left .heading-style-h4 {
                font-size: 1rem;
            }

            .tabs_left .text-size-small {
                font-size: 0.85rem;
            }
        }

        .tabs_left-top {
            height: 100%;
            position: relative;
        }

        .tabs_let-content {
            width: 100%;
            height: 100%;
            text-align: center;
            flex-direction: column;
            justify-content: space-around;
            padding-top: 0%;
            padding-bottom: 0%;
            display: flex;
            position: absolute;
            opacity: 0;
            transition: opacity 0.8s ease, transform 0.8s ease;
            transform: translateY(20px);
        }

        .tabs_let-content.is-1 {
            opacity: 1;
            transform: translateY(0);
        }

        .heading-style-h4 {
            letter-spacing: -.02em;
            font-size: 2.125rem;
            font-weight: 500;
            line-height: 1.05;
            margin: 0;
        }

        .text-color-gray100 {
            color: white;
        }

        .text-color-gold {
            color: var(--accent-gold);
        }

        .tabs_line {
            width: 100%;
            height: 1px;
            background-color: rgba(255, 255, 255, 0.3);
            margin: 1rem 0;
        }

        .text-color-gray400 {
            color: rgba(255, 255, 255, 0.8);
        }

        .text-size-small {
            font-size: 1rem;
            line-height: 1.6;
        }

        .tabs_left-bottom {
            flex-direction: column;
            display: flex;
            margin-top: 2rem;
        }

        .button {
            grid-column-gap: 0.5rem;
            grid-row-gap: 0.5rem;
            border: 2px solid var(--accent-gold);
            background-color: transparent;
            color: white;
            text-align: center;
            letter-spacing: 0.03em;
            text-transform: uppercase;
            cursor: pointer;
            border-radius: 0.6rem;
            justify-content: center;
            align-items: center;
            padding: 0.8rem 1.5rem;
            font-size: 0.875rem;
            text-decoration: none;
            transition: all 0.3s ease;
            display: flex;
            overflow: hidden;
            position: relative;
            font-weight: 600;
        }

        .button.is-gold.is-secondary {
            color: white;
            background-color: transparent;
            border-color: var(--accent-gold);
        }

        .button.is-gold.is-secondary:hover {
            color: var(--primary-green);
            border-color: var(--accent-gold);
            background-color: var(--accent-gold);
        }

        .button-text {
            z-index: 2;
            position: relative;
        }

        .button-circle-wrapper {
            width: 1.25rem;
            height: 1.25rem;
            border-radius: 100vw;
            justify-content: center;
            align-items: center;
            display: flex;
            position: relative;
            overflow: hidden;
        }

        .button-icon {
            z-index: 2;
            width: 1rem;
            height: 1rem;
            justify-content: center;
            align-items: center;
            display: flex;
            position: absolute;
        }

        .button-circlee {
            width: 80%;
            aspect-ratio: 1 / 1;
            border-radius: 50%;
            position: absolute;
            top: 0;
            transform: translate3d(0px, 0%, 0px) scale3d(1, 1, 1);
            height: 250px;
            transition: all 0.2s ease-in-out;
            will-change: transform, width, height;
            border: 1px solid transparent;
        }

        .background-color-gold {
            background-color: var(--accent-gold);
        }

        .tabs_right {
            width: 100%;
            height: 100%;
            border-radius: 20px;
            position: relative;
            overflow: hidden;
        }

        .tabs_image {
            width: 100%;
            height: 100%;
            border-radius: 20px;
            position: absolute;
            opacity: 0;
            transform: translateY(50px) scale(0.95);
            transition: opacity 0.8s ease, transform 0.8s ease;
            overflow: hidden;
        }

        .tabs_image.is-1 {
            opacity: 1;
            transform: translateY(0) scale(1);
        }

        .tabs_image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 20px;
        }

        .tabs_badge {
            position: absolute;
            top: 2rem;
            right: 2rem;
            background: rgba(255, 215, 0, 0.9);
            color: var(--primary-green);
            padding: 0.8rem 1.2rem;
            border-radius: 15px;
            font-weight: 600;
            font-size: 0.875rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            backdrop-filter: blur(10px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .tabs_component {
                grid-template-columns: 1fr;
                grid-template-rows: auto auto;
                gap: 1rem;
            }

            .heading-style-h4 {
                font-size: 1.5rem;
            }

            .text-size-small {
                font-size: 0.875rem;
            }

            .tabs_height {
                height: 650vh;
            }

            .padding-section-large {
                padding-top: 2rem;
                padding-bottom: 0rem;
            }
        }

        /* Floating Education Icons */
        .floating-edu-icons {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            pointer-events: none;
            z-index: 1;
        }

        .edu-icon {
            position: absolute;
            font-size: 8rem;
            color: rgba(2, 64, 0, 0.557);
            /* เงาลางๆ */
            filter: blur(2px);
            opacity: 0.25;
            transition: transform 2s;
            will-change: transform;
            animation: eduIconFloat 8s infinite alternate;
        }

        @keyframes eduIconFloat {
            0% {
                transform: translateY(0px) scale(1);
            }

            100% {
                transform: translateY(30px) scale(1.05);
            }
        }

        .section-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary-green);
            margin-top: 2rem;
        }

        .info-card {
            border-left: 5px solid var(--primary-green);
            background: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 1rem 1.5rem;
            border-radius: 0.5rem;
            margin-bottom: 1rem;
        }

        .info-icon {
            color: var(--secondary-green);
            margin-right: 0.5rem;
        }

        /* Custom styles for the information section */
        #infomations {
            background: linear-gradient(135deg, #f8fffa 0%, #e0ffe0 100%);
            position: relative;
        }

        #infomations .container {
            backdrop-filter: blur(8px);
            border-radius: 2rem;
            box-shadow: 0 8px 32px rgba(40, 161, 13, 0.12);
        }

        #infomations h1 {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--primary-green);
            letter-spacing: -1px;
        }

        #infomations h1 i {
            color: var(--accent-gold);
            margin-right: 10px;
        }

        #infomations h2 {
            margin-top: 2rem;
            font-size: 1.7rem;
            color: var(--primary-green);
            font-weight: 700;
        }

        #infomations h2 i {
            color: var(--accent-gold);
            margin-right: 8px;
            font-size: 1.5rem;
        }

        .modern-card {
            background: rgba(255, 255, 255, 0.85);
            border-radius: 1.2rem;
            box-shadow: 0 4px 24px rgba(40, 161, 13, 0.08);
            border-left: 6px solid var(--accent-gold);
            padding: 1.2rem 1.7rem;
            margin-bottom: 1rem;
            font-size: 1.15rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            transition: box-shadow 0.3s, transform 0.3s;
            position: relative;
            overflow: hidden;
        }

        .modern-card:hover {
            box-shadow: 0 8px 32px rgba(40, 161, 13, 0.18);
            transform: translateY(-4px) scale(1.03);
            background: linear-gradient(90deg, #fffbe7 60%, #e0ffe0 100%);
        }

        .modern-card .info-icon {
            font-size: 1.7rem;
            margin-right: 1rem;
            color: var(--primary-green);
            filter: drop-shadow(0 2px 6px rgba(40, 161, 13, 0.12));
            transition: color 0.3s;
        }

        .modern-card:hover .info-icon {
            color: var(--accent-gold);
        }

        @media (max-width: 768px) {
            .container {
                padding: 1.5rem !important;
            }

            .modern-card {
                font-size: 1rem;
                padding: 1rem 1rem;
            }

            h1.text-center {
                font-size: 1.5rem !important;
            }
        }

        .info-btn {
            cursor: pointer;
            transition: box-shadow 0.2s, transform 0.2s, background 0.2s;
            background: #fff;
            border: none;
            border-radius: 1.2rem;
            box-shadow: 0 2px 8px rgba(40, 161, 13, 0.10);
            padding: 1.1rem 1.5rem;
            margin-bottom: 1rem;
            width: 100%;
            text-align: left;
            font-size: 1.08rem;
            color: var(--primary-green);
            font-weight: 500;
            outline: none;
            position: relative;
        }

        .info-btn:focus {
            box-shadow: 0 0 0 3px rgba(40, 161, 13, 0.18);
            background: #f4fff4;
        }

        .info-btn:hover {
            box-shadow: 0 4px 16px rgba(40, 161, 13, 0.18);
            transform: translateY(-2px) scale(1.02);
            background: #f8fff8;
        }

        .info-btn .info-icon {
            margin-right: 0.8rem;
            font-size: 1.3em;
            vertical-align: middle;
            color: var(--accent-gold);
            transition: color 0.2s;
        }

        .info-btn:hover .info-icon {
            color: var(--primary-green);
        }

        .event-status {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1rem;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 2rem;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .status-badge {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            background: linear-gradient(135deg, #28a10d, #34c759);
            color: white;
            padding: 0.8rem 1.5rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1.1rem;
            animation: pulse 2s infinite;
        }

        .status-badge i {
            font-size: 1.2rem;
        }

        .event-dates {
            text-align: center;
        }

        .date-item {
            color: white;
        }

        .date-number {
            font-size: 3rem;
            font-weight: 800;
            display: block;
            line-height: 1;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .date-label {
            font-size: 1.1rem;
            font-weight: 500;
            margin-top: 0.5rem;
            opacity: 0.9;
        }

        .event-location {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: white;
            font-size: 0.95rem;
            text-align: center;
            opacity: 0.9;
        }

        .event-location i {
            color: var(--accent-gold);
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }
        }

        @media (max-width: 768px) {
            .event-status {
                padding: 1.5rem;
            }

            .date-number {
                font-size: 2.5rem;
            }

            .status-badge {
                font-size: 1rem;
                padding: 0.6rem 1.2rem;
            }

            .event-location {
                font-size: 0.9rem;
            }
        }

        /* Modern Competition Results Modal */
        .competition-modal {
            display: none;
            position: fixed;
            z-index: 10000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(10px);
            animation: modalFadeIn 0.3s ease-out;
        }

        .competition-modal.show {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background: linear-gradient(135deg, #ffffff 0%, #f8fff8 100%);
            margin: auto;
            padding: 0;
            border: none;
            border-radius: 20px;
            width: 90%;
            max-width: 800px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 25px 50px rgba(40, 161, 13, 0.2);
            position: relative;
            transform: scale(0.7);
            animation: modalZoomIn 0.4s ease-out forwards;
        }

        .modal-header {
            background: linear-gradient(135deg, var(--primary-green), var(--secondary-green));
            color: white;
            padding: 1.5rem 2rem;
            border-radius: 20px 20px 0 0;
            position: relative;
            overflow: hidden;
        }

        .modal-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255, 215, 0, 0.1) 0%, transparent 70%);
            animation: headerGlow 3s ease-in-out infinite alternate;
        }

        .modal-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.8rem;
            position: relative;
            z-index: 2;
        }

        .modal-title i {
            font-size: 1.8rem;
            color: var(--accent-gold);
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .close-btn {
            position: absolute;
            top: 1rem;
            right: 1.5rem;
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
            font-size: 1.5rem;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            z-index: 3;
        }

        .close-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: rotate(90deg);
        }

        .modal-body {
            padding: 2rem;
        }

        .award-section {
            margin-bottom: 2rem;
        }

        .award-title {
            font-size: 1.3rem;
            font-weight: 600;
            color: var(--primary-green);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .award-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            box-shadow: 0 5px 15px rgba(40, 161, 13, 0.1);
            border-left: 5px solid;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .award-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, transparent 0%, rgba(255, 215, 0, 0.05) 50%, transparent 100%);
            transform: translateX(-100%);
            transition: transform 0.6s ease;
        }

        .award-card:hover::before {
            transform: translateX(100%);
        }

        .award-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(40, 161, 13, 0.2);
        }

        .award-card.first-place {
            border-left-color: #FFD700;
            background: linear-gradient(135deg, #fff9e6 0%, white 100%);
        }

        .award-card.second-place {
            border-left-color: #C0C0C0;
            background: linear-gradient(135deg, #f8f8f8 0%, white 100%);
        }

        .award-card.third-place {
            border-left-color: #CD7F32;
            background: linear-gradient(135deg, #fff5f0 0%, white 100%);
        }

        .award-card.honorable-mention {
            border-left-color: var(--primary-green);
            background: linear-gradient(135deg, #f0fff0 0%, white 100%);
        }

        .award-rank {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            margin-bottom: 0.8rem;
        }

        .rank-badge {
            background: linear-gradient(135deg, var(--accent-gold), #ffd700);
            color: var(--primary-green);
            padding: 0.4rem 1rem;
            border-radius: 25px;
            font-weight: 700;
            font-size: 0.9rem;
            text-shadow: none;
            box-shadow: 0 3px 10px rgba(255, 215, 0, 0.3);
        }

        .rank-badge.second {
            background: linear-gradient(135deg, #C0C0C0, #e8e8e8);
            color: #444;
        }

        .rank-badge.third {
            background: linear-gradient(135deg, #CD7F32, #d4941e);
            color: white;
        }

        .rank-badge.mention {
            background: linear-gradient(135deg, var(--primary-green), var(--secondary-green));
            color: white;
        }

        .award-icon {
            font-size: 2rem;
            color: var(--accent-gold);
            animation: awardGlow 2s ease-in-out infinite alternate;
        }

        .award-icon.second {
            color: #C0C0C0;
        }

        .award-icon.third {
            color: #CD7F32;
        }

        .award-icon.mention {
            color: var(--primary-green);
        }

        .school-info {
            margin-left: 3rem;
        }

        .school-name {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--primary-green);
            margin-bottom: 0.3rem;
        }

        .team-name {
            font-size: 1rem;
            font-weight: 700;
            color: var(--accent-gold);
            margin-bottom: 0.3rem;
        }

        .school-affiliation {
            font-size: 0.9rem;
            color: #666;
            font-style: italic;
        }

        @keyframes modalFadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes modalZoomIn {
            from {
                transform: scale(0.7);
                opacity: 0;
            }

            to {
                transform: scale(1);
                opacity: 1;
            }
        }

        @keyframes headerGlow {
            0% {
                opacity: 0.5;
            }

            100% {
                opacity: 0.8;
            }
        }

        @keyframes awardGlow {
            0% {
                filter: drop-shadow(0 0 5px currentColor);
                transform: scale(1);
            }

            100% {
                filter: drop-shadow(0 0 15px currentColor);
                transform: scale(1.05);
            }
        }

        @media (max-width: 768px) {
            .modal-content {
                width: 95%;
                margin: 1rem;
            }

            .modal-header {
                padding: 1rem 1.5rem;
            }

            .modal-title {
                font-size: 1.2rem;
            }

            .modal-body {
                padding: 1.5rem;
            }

            .school-info {
                margin-left: 0;
                margin-top: 1rem;
            }

            .award-rank {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }
        }
    </style>
</head>

<body>
    <!-- Page Loader -->
    <div class="page-loader" id="pageLoader">
        <div class="loader-content">
            <div class="loader-logo">
                <img src="{{ asset('assets/images/yala-edu-fest/yalaedufest.png') }}" alt="YALA EDU-FEST 2025"
                    style="width: 100%; height: 100%; object-fit: contain;" />
            </div>
            <div class="loader-text">กำลังโหลด...</div>
        </div>
    </div>

    <a href="{{ route('home.index') }}" class="back-btn">
        <i class="fas fa-arrow-left"></i> กลับหน้าหลัก
    </a>

    <!-- Sticky Navigation -->
    <nav class="sticky-navigation" id="stickyNav">
        <a href="#hero" class="sticky-nav-item active" data-section="hero">
            <i class="fas fa-home"></i>
            <span class="sticky-nav-tooltip">หน้าหลัก</span>
        </a>
        <a href="#introWrapper" class="sticky-nav-item" data-section="introWrapper">
            <i class="fas fa-info-circle"></i>
            <span class="sticky-nav-tooltip">เกี่ยวกับงาน</span>
        </a>
        <a href="#section_tabs" class="sticky-nav-item" data-section="section_tabs">
            <i class="fas fa-calendar"></i>
            <span class="sticky-nav-tooltip">ข้อมูลงาน</span>
        </a>
        <a href="#infomations" class="sticky-nav-item" data-section="infomations">
            <i class="fa-solid fa-circle-info"></i>
            <span class="sticky-nav-tooltip">รายละเอียดงาน</span>
        </a>
        <a href="#floor-plan" class="sticky-nav-item" data-section="floor-plan">
            <i class="fa-solid fa-map-location"></i>
            <span class="sticky-nav-tooltip">แผนผังการจัดงาน</span>
        </a>
    </nav>

    <!-- Hero Section - Full Screen -->
    <section class="hero-section" id="hero">
        <!-- Floating Background Elements -->
        <div class="floating-elements">
            <div class="floating-circle"></div>
            <div class="floating-circle"></div>
            <div class="floating-circle"></div>
            <!-- Animated particles -->
            <div class="particles">
                <div class="particle"></div>
                <div class="particle"></div>
                <div class="particle"></div>
                <div class="particle"></div>
                <div class="particle"></div>
                <div class="particle"></div>
                <div class="particle"></div>
                <div class="particle"></div>
            </div>
            <!-- Floating Education Icons -->
            <div class="floating-edu-icons">
                <i class="fas fa-graduation-cap edu-icon" style="top:10%; left:15%;"></i>
                <i class="fas fa-book edu-icon" style="top:60%; left:70%;"></i>
                <i class="fas fa-chalkboard-teacher edu-icon" style="top:40%; left:30%;"></i>
                <i class="fas fa-lightbulb edu-icon" style="top:75%; left:20%;"></i>
            </div>
        </div>

        <div class="hero-content">
            <!-- Logo -->
            <div class="logo-container">
                <div class="logo">
                    <img src="{{ asset('assets/images/yala-edu-fest/yalaedufest.png') }}" alt="YALA EDU-FEST 2025"
                        style="width: 100%; height: 100%; object-fit: contain;" />
                </div>
            </div>

            <!-- Title -->
            <h1 class="festival-title">มหกรรมวิชาการ<br>YALA EDU-FEST 2025</h1>
            <p class="festival-subtitle">สานพลังการศึกษา ยะลาเป็นหนึ่ง</p>
            <p class="festival-subtitle">พลิกโฉมคุณภาพด้วยนวัตกรรม</p>

            <!-- Countdown in Hero -->
            <div class="hero-countdown">
                <div class="event-status">
                    <div class="status-badge">
                        <i class="fas fa-calendar-check"></i>
                        <span class="status-text">สิ้นสุดลงแล้ว</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Scroll Indicator -->
        <div class="scroll-indicator" onclick="document.getElementById('js-pin').scrollIntoView()">
            <i class="fas fa-chevron-down"></i>
        </div>
    </section>

    <!-- Sticky Tabs Section -->
    <section class="intro-wrapper" id="introWrapper">
        <div class="intro">
            <div class="text-align-center" id="js-pin">
                <div class="max-width-big align-center">
                    <div class="margin-bottom margin-small">
                        <h2 class="heading-style-h3">
                            {{-- 🎓 เกี่ยวกับงาน --}}
                            <i class="fas fa-broadcast-tower" style="color:var(--accent-gold); margin-right:8px;"></i>
                            รายละเอียดงาน
                        </h2>
                    </div>
                    <p class="text-size-medium">
                        <strong>YALA EDU-FEST 2025</strong> เป็นมหกรรมวิชาการที่รวบรวมสถานศึกษา นักเรียน นักศึกษา
                        และผู้สนใจด้านการศึกษาเข้าร่วมกิจกรรมต่างๆ เพื่อสานพลังการศึกษา ยะลาเป็นหนึ่ง
                        และพลิกโฉมคุณภาพด้วยนวัตกรรม
                    </p>
                </div>
            </div>
        </div>
    </section>


    <section class="section_tabs" id="section_tabs">
        <div class="padding-section-large">
            <div class="tabs_height">
                <div class="tabs_sticky-wrapper">
                    <div class="tabs_container">
                        <div class="tabs_component">
                            <div class="tabs_left">
                                <div class="tabs_left-top">
                                    <!-- easter -->
                                    <div class="tabs_let-content is-1">
                                        <h2 class="heading-style-h4 text-color-gray100">
                                            ข้อมูล <span class="text-color-gold">งาน</span>
                                        </h2>
                                        <div class="tabs_line"></div>
                                        <p class="text-size-small text-color-gray400">
                                            <strong>เว็บไซต์ทำโดย:</strong> นายสุธาวี สะอะ<br><br>
                                            <strong>Github:</strong> https://github.com/aun-suthawee<br><br>
                                        </p>
                                    </div>

                                    <!-- ข้อมูลงาน -->
                                    <div class="tabs_let-content is-2">
                                        <h2 class="heading-style-h4 text-color-gray100">
                                            ข้อมูล <span class="text-color-gold">งาน</span>
                                        </h2>
                                        <div class="tabs_line"></div>
                                        <p class="text-size-small text-color-gray400">
                                            📅 <strong>วันที่:</strong> 5-6 สิงหาคม พ.ศ. 2568 (วันอังคาร -
                                            วันพุธ)<br><br>
                                            📍 <strong>สถานที่:</strong> อาคารหอประชุมเฉลิมพระเกียรติ 80 พรรษา 5 ธันวาคม
                                            2550
                                            มหาวิทยาลัยราชภัฏยะลา<br><br>
                                            🎟️ <strong>การเข้าร่วม:</strong> เข้าร่วมได้ฟรี! ไม่ต้องสมัครล่วงหน้า
                                            ทุกสถานศึกษา นักเรียน นักศึกษา
                                        </p>
                                    </div>

                                    <!-- การแข่งขัน -->
                                    <div class="tabs_let-content is-3">
                                        <h2 class="heading-style-h4 text-color-gray100">
                                            การแข่งขัน <span class="text-color-gold">Competition</span>
                                        </h2>
                                        <div class="tabs_line"></div>
                                        <p class="text-size-small text-color-gray400">
                                            <i class="fas fa-trophy"></i><strong> การแข่งขันในงาน</strong><br><br>
                                            • มัคคุเทศก์น้อย<br>
                                            • ร้องเพลงพระราชนิพนธ์<br>
                                            • การเล่านิทานประกอบท่าทาง<br><br>
                                            การแข่งขันเหล่านี้มุ่งเน้นการพัฒนาทักษะการสื่อสาร ความคิดสร้างสรรค์
                                            และการแสดงออกของเด็กและเยาวชน
                                        </p>
                                    </div>

                                    <!-- การแสดงของนักเรียน -->
                                    <div class="tabs_let-content is-4">
                                        <h2 class="heading-style-h4 text-color-gray100">
                                            การแสดง <span class="text-color-gold">นักเรียน</span>
                                        </h2>
                                        <div class="tabs_line"></div>
                                        <p class="text-size-small text-color-gray400">
                                            🎭 <strong>Children's Show</strong><br><br>
                                            การแสดงศิลปะและความสามารถของนักเรียนจากสถานศึกษาต่างๆ<br><br>
                                            • การแสดงดนตรี นาฏศิลป์<br>
                                            • การแสดงละคร การเล่านิทาน<br>
                                            • การแสดงพื้นบ้านและวัฒนธรรมท้องถิ่น<br>
                                            • การแสดงแฟชั่นโชว์ของนักเรียน
                                        </p>
                                    </div>

                                    <!-- การประกวดนวัตกรรมที่เป็นเลิศ -->
                                    <div class="tabs_let-content is-5">
                                        <h2 class="heading-style-h4 text-color-gray100">
                                            นวัตกรรม <span class="text-color-gold">ที่เป็นเลิศ</span>
                                        </h2>
                                        <div class="tabs_line"></div>
                                        <p class="text-size-small text-color-gray400">
                                            💡<strong>Best Practices</strong><br><br>
                                            การประกวดนวัตกรรมและแนวทางปฏิบัติที่ดีในด้านการศึกษา<br><br>
                                            • โครงการนวัตกรรมการเรียนการสอน<br>
                                            • เทคโนโลยีเพื่อการศึกษา<br>
                                            • สื่อการเรียนรู้ดิจิทัล<br>
                                            • แนวทางการพัฒนาคุณภาพการศึกษา
                                        </p>
                                    </div>

                                    <!-- การแสดงผลงาน/นิทรรศการ -->
                                    <div class="tabs_let-content is-6">
                                        <h2 class="heading-style-h4 text-color-gray100">
                                            นิทรรศการ <span class="text-color-gold">ผลงาน</span>
                                        </h2>
                                        <div class="tabs_line"></div>
                                        <p class="text-size-small text-color-gray400">
                                            🖼️ <strong>Exhibition</strong><br><br>
                                            นิทรรศการแสดงผลงานและโครงการของนักเรียนและสถานศึกษา<br><br>
                                            • ผลงานวิจัยและโครงงานของนักเรียน<br>
                                            • นิทรรศการผลิตภัณฑ์ชุมชน<br>
                                            • ผลงานศิลปกรรมและหัตถกรรม<br>
                                            • แสดงผลงานเทคโนโลยีและนวัตกรรม
                                        </p>
                                    </div>

                                    <!-- การเสวนาวิชาการ -->
                                    <div class="tabs_let-content is-7">
                                        <h2 class="heading-style-h4 text-color-gray100">
                                            เสวนา <span class="text-color-gold">วิชาการ</span>
                                        </h2>
                                        <div class="tabs_line"></div>
                                        <p class="text-size-small text-color-gray400">
                                            💬 <strong>Seminar</strong><br><br>
                                            การเสวนาและการแลกเปลี่ยนความรู้ทางวิชาการด้านการศึกษา<br><br>
                                            • การปฏิรูปการศึกษาในศตวรรษที่ 21<br>
                                            • การพัฒนาครูและบุคลากรทางการศึกษา<br>
                                            • แนวทางการจัดการเรียนรู้ใหม่<br>
                                            • การประเมินและการวัดผลการศึกษา
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="tabs_right">
                                <!-- easter -->
                                <div class="tabs_image is-1">

                                </div>

                                <!-- ข้อมูลงาน -->
                                <div class="tabs_image is-2">
                                    <div id="carousel-competition-2" class="carousel slide h-100"
                                        data-bs-ride="carousel" data-bs-interval="2500">
                                        <div class="carousel-inner h-100">
                                            <div class="carousel-item active h-100">
                                                <img src="assets\images\yala-edu-fest\yala-edu-info.jpg"
                                                    class="d-block w-100 h-100" alt="ข้อมูลงาน1"
                                                    style="object-fit:cover; border-radius:20px;">
                                            </div>
                                            <div class="carousel-item active h-100">
                                                <img src="assets\images\yala-edu-fest\timeline\timeline-1.png?rand=<?= md5(time()) ?>"
                                                    class="d-block w-100 h-100" alt="ข้อมูลงาน2"
                                                    style="object-fit:cover; border-radius:20px;">
                                            </div>
                                            <div class="carousel-item active h-100">
                                                <img src="assets\images\yala-edu-fest\timeline\timeline-2.png?rand=<?= md5(time()) ?>"
                                                    class="d-block w-100 h-100" alt="ข้อมูลงาน3"
                                                    style="object-fit:cover; border-radius:20px;">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tabs_badge">
                                        <span>ข้อมูลงาน</span>
                                    </div>
                                </div>

                                <!-- การแข่งขัน -->
                                <div class="tabs_image is-3">
                                    <div id="carousel-competition-3" class="carousel slide h-100"
                                        data-bs-ride="carousel" data-bs-interval="2500">
                                        <div class="carousel-inner h-100">
                                            <div class="carousel-item active h-100">
                                                <img src="assets\images\yala-edu-fest\music-show\music-show-1.jpg?rand=<?= md5(time()) ?>"
                                                    class="d-block w-100 h-100" alt="การแข่งขัน1"
                                                    style="object-fit:cover; border-radius:20px;">
                                            </div>
                                            <div class="carousel-item h-100">
                                                <img src="assets\images\yala-edu-fest\music-show\music-show-2.jpg?rand=<?= md5(time()) ?>"
                                                    class="d-block w-100 h-100" alt="การแข่งขัน2"
                                                    style="object-fit:cover; border-radius:20px;">
                                            </div>
                                            <div class="carousel-item h-100">
                                                <img src="assets\images\yala-edu-fest\music-show\music-show-3.jpg?rand=<?= md5(time()) ?>"
                                                    class="d-block w-100 h-100" alt="การแข่งขัน3"
                                                    style="object-fit:cover; border-radius:20px;">
                                            </div>
                                            <div class="carousel-item h-100">
                                                <img src="assets\images\yala-edu-fest\little-guide\little-guide-1.jpg?rand=<?= md5(time()) ?>"
                                                    class="d-block w-100 h-100" alt="การแข่งขัน4"
                                                    style="object-fit:cover; border-radius:20px;">
                                            </div>
                                            <div class="carousel-item h-100">
                                                <img src="assets\images\yala-edu-fest\little-guide\little-guide-2.jpg?rand=<?= md5(time()) ?>"
                                                    class="d-block w-100 h-100" alt="การแข่งขัน5"
                                                    style="object-fit:cover; border-radius:20px;">
                                            </div>
                                            <div class="carousel-item h-100">
                                                <img src="assets\images\yala-edu-fest\little-guide\little-guide-3.jpg?rand=<?= md5(time()) ?>"
                                                    class="d-block w-100 h-100" alt="การแข่งขัน6"
                                                    style="object-fit:cover; border-radius:20px;">
                                            </div>
                                            <div class="carousel-item h-100">
                                                <img src="assets\images\yala-edu-fest\storytelling\storytelling-1.jpg?rand=<?= md5(time()) ?>"
                                                    class="d-block w-100 h-100" alt="การแข่งขัน7"
                                                    style="object-fit:cover; border-radius:20px;">
                                            </div>
                                            <div class="carousel-item h-100">
                                                <img src="assets\images\yala-edu-fest\storytelling\storytelling-2.jpg?rand=<?= md5(time()) ?>"
                                                    class="d-block w-100 h-100" alt="การแข่งขัน8"
                                                    style="object-fit:cover; border-radius:20px;">
                                            </div>
                                            <div class="carousel-item h-100">
                                                <img src="assets\images\yala-edu-fest\storytelling\storytelling-3.jpg?rand=<?= md5(time()) ?>"
                                                    class="d-block w-100 h-100" alt="การแข่งขัน9"
                                                    style="object-fit:cover; border-radius:20px;">
                                            </div>
                                            <div class="carousel-item h-100">
                                                <img src="assets\images\yala-edu-fest\storytelling\storytelling-4.jpg?rand=<?= md5(time()) ?>"
                                                    class="d-block w-100 h-100" alt="การแข่งขัน10"
                                                    style="object-fit:cover; border-radius:20px;">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tabs_badge">
                                        <i class="fas fa-trophy"></i>
                                        <span>การแข่งขัน</span>
                                    </div>
                                </div>

                                <!-- การแสดงของนักเรียน -->
                                <div class="tabs_image is-4">
                                    <div id="carousel-show-4" class="carousel slide h-100" data-bs-ride="carousel"
                                        data-bs-interval="2500">
                                        <div class="carousel-inner h-100">
                                            <div class="carousel-item active h-100">
                                                <img src="assets\images\yala-edu-fest\student-show\student-show-1.jpg?rand=<?= md5(time()) ?>"
                                                    class="d-block w-100 h-100" alt="student-show-1"
                                                    style="object-fit:cover; border-radius:20px;">
                                            </div>
                                            <div class="carousel-item h-100">
                                                <img src="assets\images\yala-edu-fest\student-show\student-show-2.jpg?rand=<?= md5(time()) ?>"
                                                    class="d-block w-100 h-100" alt="student-show-2"
                                                    style="object-fit:cover; border-radius:20px;">
                                            </div>
                                            <div class="carousel-item h-100">
                                                <img src="assets\images\yala-edu-fest\student-show\student-show-3.jpg?rand=<?= md5(time()) ?>"
                                                    class="d-block w-100 h-100" alt="student-show-3"
                                                    style="object-fit:cover; border-radius:20px;">
                                            </div>
                                            <div class="carousel-item h-100">
                                                <img src="assets\images\yala-edu-fest\student-show\student-show-4.jpg?rand=<?= md5(time()) ?>"
                                                    class="d-block w-100 h-100" alt="student-show-4"
                                                    style="object-fit:cover; border-radius:20px;">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tabs_badge">
                                        <i class="fas fa-theater-masks"></i>
                                        <span>การแสดงนักเรียน</span>
                                    </div>
                                </div>

                                <!-- นวัตกรรมที่เป็นเลิศ -->
                                <div class="tabs_image is-5">
                                    <div id="carousel-innovation-5" class="carousel slide h-100"
                                        data-bs-ride="carousel" data-bs-interval="2500">
                                        <div class="carousel-inner h-100">
                                            <div class="carousel-item active h-100">
                                                <img src="assets\images\yala-edu-fest\inovation\innovation-1.jpg?rand=<?= md5(time()) ?>"
                                                    class="d-block w-100 h-100" alt="นวัตกรรมที่เป็นเลิศ1"
                                                    style="object-fit:cover; border-radius:20px;">
                                            </div>
                                            <div class="carousel-item h-100">
                                                <img src="assets\images\yala-edu-fest\inovation\innovation-2.jpg?rand=<?= md5(time()) ?>"
                                                    class="d-block w-100 h-100" alt="นวัตกรรมที่เป็นเลิศ2"
                                                    style="object-fit:cover; border-radius:20px;">
                                            </div>
                                            <div class="carousel-item h-100">
                                                <img src="assets\images\yala-edu-fest\inovation\innovation-3.jpg?rand=<?= md5(time()) ?>"
                                                    class="d-block w-100 h-100" alt="นวัตกรรมที่เป็นเลิศ3"
                                                    style="object-fit:cover; border-radius:20px;">
                                            </div>
                                            <div class="carousel-item h-100">
                                                <img src="assets\images\yala-edu-fest\inovation\innovation-4.jpg?rand=<?= md5(time()) ?>"
                                                    class="d-block w-100 h-100" alt="นวัตกรรมที่เป็นเลิศ4"
                                                    style="object-fit:cover; border-radius:20px;">
                                            </div>
                                            <div class="carousel-item h-100">
                                                <img src="assets\images\yala-edu-fest\inovation\innovation-5.jpg?rand=<?= md5(time()) ?>"
                                                    class="d-block w-100 h-100" alt="นวัตกรรมที่เป็นเลิศ5"
                                                    style="object-fit:cover; border-radius:20px;">
                                            </div>
                                            <div class="carousel-item h-100">
                                                <img src="assets\images\yala-edu-fest\inovation\innovation-6.jpg?rand=<?= md5(time()) ?>"
                                                    class="d-block w-100 h-100" alt="นวัตกรรมที่เป็นเลิศ6"
                                                    style="object-fit:cover; border-radius:20px;">
                                            </div>
                                            <div class="carousel-item h-100">
                                                <img src="assets\images\yala-edu-fest\inovation\innovation-7.jpg?rand=<?= md5(time()) ?>"
                                                    class="d-block w-100 h-100" alt="นวัตกรรมที่เป็นเลิศ7"
                                                    style="object-fit:cover; border-radius:20px;">
                                            </div>
                                            <div class="carousel-item h-100">
                                                <img src="assets\images\yala-edu-fest\inovation\innovation-8.jpg?rand=<?= md5(time()) ?>"
                                                    class="d-block w-100 h-100" alt="นวัตกรรมที่เป็นเลิศ8"
                                                    style="object-fit:cover; border-radius:20px;">
                                            </div>
                                            <div class="carousel-item h-100">
                                                <img src="assets\images\yala-edu-fest\inovation\innovation-9.jpg?rand=<?= md5(time()) ?>"
                                                    class="d-block w-100 h-100" alt="นวัตกรรมที่เป็นเลิศ9"
                                                    style="object-fit:cover; border-radius:20px;">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tabs_badge">
                                        <i class="fas fa-lightbulb"></i>
                                        <span>Best Practices</span>
                                    </div>
                                </div>

                                <!-- นิทรรศการผลงาน -->
                                <div class="tabs_image is-6">
                                    <div id="carousel-exhibition-6" class="carousel slide h-100"
                                        data-bs-ride="carousel" data-bs-interval="2500">
                                        <div class="carousel-inner h-100">
                                            <div class="carousel-item active h-100">
                                                <img src="assets\images\yala-edu-fest\exhibition\exhibition-1.jpg?rand=<?= md5(time()) ?>"
                                                    class="d-block w-100 h-100" alt="นิทรรศการผลงาน1"
                                                    style="object-fit:cover; border-radius:20px;">
                                            </div>
                                            <div class="carousel-item h-100">
                                                <img src="assets\images\yala-edu-fest\exhibition\exhibition-2.jpg?rand=<?= md5(time()) ?>"
                                                    class="d-block w-100 h-100" alt="นิทรรศการผลงาน2"
                                                    style="object-fit:cover; border-radius:20px;">
                                            </div>
                                            <div class="carousel-item h-100">
                                                <img src="assets\images\yala-edu-fest\exhibition\exhibition-3.jpg?rand=<?= md5(time()) ?>"
                                                    class="d-block w-100 h-100" alt="นิทรรศการผลงาน3"
                                                    style="object-fit:cover; border-radius:20px;">
                                            </div>
                                            <div class="carousel-item h-100">
                                                <img src="assets\images\yala-edu-fest\exhibition\exhibition-4.jpg?rand=<?= md5(time()) ?>"
                                                    class="d-block w-100 h-100" alt="นิทรรศการผลงาน4"
                                                    style="object-fit:cover; border-radius:20px;">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tabs_badge">
                                        <i class="fas fa-image"></i>
                                        <span>นิทรรศการ</span>
                                    </div>
                                </div>

                                <!-- การเสวนาวิชาการ -->
                                <div class="tabs_image is-7">
                                    <div id="carousel-seminar-7" class="carousel slide h-100" data-bs-ride="carousel"
                                        data-bs-interval="2500">
                                        <div class="carousel-inner h-100">
                                            <div class="carousel-item active h-100">
                                                <img src="assets\images\yala-edu-fest\seminar\seminar.jpg?rand=<?= md5(time()) ?>"
                                                    class="d-block w-100 h-100" alt="การเสวนาวิชาการ1"
                                                    style="object-fit:cover; border-radius:20px;">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tabs_badge">
                                        <i class="fas fa-comments"></i>
                                        <span>เสวนาวิชาการ</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- <section class="merchandise">
        <div class="container">
            <h2 class="text-center mb-4" style="color: var(--primary-green); font-weight: 700;">
                เข้าร่วมงานเพื่อลุ้นรับของที่ระลึกสุดพิเศษ มีเฉพาะงานนี้เท่านั้น!
            </h2>
            <div class="row justify-content-between align-items-center mb-2" style="gap:0;">
                <div class="col-md-6 d-flex align-items-center p-0 merch-animate">
                    <div class="merch-img-wrapper me-3">
                        <img src="{{ asset('assets/images/yala-edu-fest/merchandise/shirt.png') }}"
                            alt="เสื้อ YALA EDU-FEST 2025" class="img-fluid merch-img">
                    </div>
                    <h3 class="merch-title mb-0" style="font-size:2rem; color:var(--primary-green); font-weight:700;">
                        เสื้อ YALA EDU-FEST 2025</h3>
                </div>
            </div>
            <div class="row justify-content-between align-items-center mb-2" style="gap:0;">
                <div class="col-md-6 d-flex flex-row-reverse align-items-center ms-auto p-0 merch-animate">
                    <div class="merch-img-wrapper ms-3">
                        <img src="{{ asset('assets/images/yala-edu-fest/merchandise/cap.png') }}"
                            alt="หมวก YALA EDU-FEST 2025" class="img-fluid merch-img">
                    </div>
                    <h3 class="merch-title mb-0" style="font-size:2rem; color:var(--primary-green); font-weight:700;">
                        หมวก YALA EDU-FEST 2025</h3>
                </div>
            </div>
            <div class="row justify-content-between align-items-center mb-2" style="gap:0;">
                <div class="col-md-6 d-flex align-items-center p-0 merch-animate">
                    <div class="merch-img-wrapper me-3">
                        <img src="{{ asset('assets/images/yala-edu-fest/merchandise/umbrella.png') }}"
                            alt="ร่ม YALA EDU-FEST 2025" class="img-fluid merch-img">
                    </div>
                    <h3 class="merch-title mb-0" style="font-size:2rem; color:var(--primary-green); font-weight:700;">
                        ร่ม YALA EDU-FEST 2025</h3>
                </div>
            </div>
            <div class="row justify-content-between align-items-center mb-2" style="gap:0;">
                <div class="col-md-6 d-flex flex-row-reverse align-items-center ms-auto p-0 merch-animate">
                    <div class="merch-img-wrapper ms-3">
                        <img src="{{ asset('assets/images/yala-edu-fest/merchandise/bag.png') }}"
                            alt="กระเป๋า YALA EDU-FEST 2025" class="img-fluid merch-img">
                    </div>
                    <h3 class="merch-title mb-0" style="font-size:2rem; color:var(--primary-green); font-weight:700;">
                        กระเป๋า YALA EDU-FEST 2025</h3>
                </div>
            </div>
        </div>
    </section> --}}

    <section id="infomations"
        style="background: linear-gradient(135deg, #f8fffa 0%, #e0ffe0 100%); position:relative;">
        <div class="container py-5"
            style="backdrop-filter: blur(8px); border-radius: 2rem; box-shadow: 0 8px 32px rgba(40,161,13,0.12);">
            <h1 class="text-center mb-5"
                style="font-size:2.5rem; font-weight:800; color:var(--primary-green); letter-spacing:-1px;">
                <i class="fa-solid fa-circle-info" style="color:var(--accent-gold); margin-right:10px;"></i>
                ข้อมูลกิจกรรมภายในงาน YALA EDU-FEST 2025
            </h1>

            <h2 class="section-title"
                style="margin-top:2rem; font-size:1.7rem; color:var(--primary-green); font-weight:700;">
                <i class="fas fa-trophy" style="color:var(--accent-gold); margin-right:8px; font-size:1.5rem;"></i>
                ประกาศผลการแข่งขันงานมหกรรมวิชาการ YALA EDU-FEST 2025<br>สานพลังการศึกษา ยะลาเป็นหนึ่ง
                พลิกโฉมคุณภาพด้วยนวัตกรรม
            </h2>
            <div class="row g-4">
                <div class="col-md-6">
                    <button class="info-card modern-card info-btn" onclick="openCompetitionModal('sdg4-innovation')"
                        aria-label="ดูรายละเอียดนวัตกรรม SDG4">
                        <i class="fas fa-globe-asia info-icon"></i>
                        นวัตกรรมที่มีผลการดำเนินงานบรรลุเป้าหมายการพัฒนาที่ยั่งยืนด้านการศึกษา (SDG4)
                    </button>
                    <button class="info-card modern-card info-btn" onclick="openCompetitionModal('sdg4-school')"
                        aria-label="ดูรายละเอียดสถานศึกษา SDG4">
                        <i class="fas fa-school info-icon"></i>
                        สถานศึกษาที่มีผลการดำเนินงานบรรลุเป้าหมายการพัฒนาที่ยั่งยืนด้านการศึกษา (SDG4)
                    </button>
                    <button class="info-card modern-card info-btn" onclick="openCompetitionModal('pisa')"
                        aria-label="ดูรายละเอียด PISA">
                        <i class="fas fa-chart-line info-icon"></i>
                        การขับเคลื่อนการพัฒนาการศึกษาตามแนวทางการประเมินสมรรถนะนักรียนมาตรฐานสากล (PISA)
                    </button>
                    <button class="info-card modern-card info-btn" onclick="openCompetitionModal('guide')"
                        aria-label="ดูรายละเอียดมัคคุเทศก์น้อย">
                        <i class="fas fa-child info-icon"></i>
                        การแข่งขันประกวดมัคคุเทศก์น้อย
                    </button>
                    <button class="info-card modern-card info-btn" onclick="openCompetitionModal('song')"
                        aria-label="ดูรายละเอียดร้องเพลงพระราชนิพนธ์">
                        <i class="fas fa-microphone info-icon"></i>
                        การประกวดร้องเพลงพระราชนิพนธ์
                    </button>
                </div>
                <div class="col-md-6">
                    <button class="info-card modern-card info-btn" onclick="openCompetitionModal('storytelling')"
                        aria-label="ดูรายละเอียดเล่านิทาน">
                        <i class="fas fa-book-open info-icon"></i>
                        การแข่งขันเล่านิทานประกอบท่าทาง
                    </button>
                    <button class="info-card modern-card info-btn" onclick="openCompetitionModal('early-childhood')"
                        aria-label="ดูรายละเอียด best practice ปฐมวัย">
                        <i class="fas fa-lightbulb info-icon"></i>
                        การคัดเลือก best practice การพัฒนาการจัดการศึกษาเด็กปฐมวัย
                    </button>
                    <button class="info-card modern-card info-btn" onclick="openCompetitionModal('soft-power')"
                        aria-label="ดูรายละเอียด Soft Power">
                        <i class="fas fa-bolt info-icon"></i>
                        ผลงานยอดเยี่ยมด้านการพัฒนานวัตกรรม Soft Power
                    </button>
                    <button class="info-card modern-card info-btn" onclick="openCompetitionModal('stem')"
                        aria-label="ดูรายละเอียด STEM">
                        <i class="fas fa-atom info-icon"></i>
                        ผลงานยอดเยี่ยมด้านการพัฒนานวัตกรรม การเรียนรู้แบบ STEM Education
                    </button>
                    <button class="info-card modern-card info-btn" onclick="openCompetitionModal('coaching')"
                        aria-label="ดูรายละเอียด Coaching">
                        <i class="fas fa-user-cog info-icon"></i>
                        ผลงานยอดเยี่ยมด้านการพัฒนานวัตกรรม ระบบแนะแนวทางสำหรับผู้เรียน (Coaching)
                    </button>
                </div>
            </div>

            <h2 class="section-title mt-5" style="font-size:1.7rem; color:var(--primary-green); font-weight:700;">
                <i class="fas fa-image" style="color:var(--accent-gold); margin-right:8px; font-size:1.5rem;"></i>
                การแสดงผลงานหรือนิทรรศการ (Show)
            </h2>
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="info-card modern-card">
                        <div class="display:flex; flex-direction:column; width:100%;">
                            <div style="display:flex; align-items:center; margin-bottom:0.5rem;">
                                <i class="fas fa-globe info-icon"></i>
                                <strong
                                    style="font-size:1.1em;">สถานศึกษาที่มีผลการดําเนินงานบรรลุเป้าหมายการพัฒนาที่ยั่งยืนด้านการศึกษา(SDG4)</strong>
                            </div>
                        </div>
                    </div>
                    <div class="info-card modern-card">
                        <div class="display:flex; flex-direction:column; width:100%;">
                            <div style="display:flex; align-items:center; margin-bottom:0.5rem;">
                                <i class="fas fa-school info-icon"></i>
                                นิทรรศการของสถานศึกษาที่ผ่านการประเมิน<br>โครงการสถานศึกษาสีขาวปลอดยาเสพติดและอบายมุข
                            </div>
                            <div style="margin-bottom:0.5rem;">
                                <strong>รายชื่อสถานศึกษา / สังกัด</strong>
                                <ul
                                    style="margin-top:0.3rem; margin-bottom:0.3rem; padding-left:1.2em; font-size:0.98em;">
                                    <li>สกร.อําเภอยะหา สกร.จ.ยะลา <strong class="school-blue">(G6)</strong></li>
                                    <li>โรงเรียนบ้านโต สังกัดสำนักงานเขตพื้นที่การศึกษาประถมศึกษายะลา เขต 3 <strong class="school-blue">(G7)</strong></li>
                                    <li>โรงเรียนจันทร์ประภัสสร์อนุสรณ์ สำนักงานเขตพื้นที่การศึกษามัธยมศึกษายะลา <strong
                                            class="school-blue">(G8)</strong></li>
                                    <li>โรงเรียนบ้านฉลองชัย สังกัดสำนักงานเขตพื้นที่การศึกษาประถมศึกษายะลา เขต 2 <strong class="school-blue">(G9)</strong>
                                    </li>
                                    <li>โรงเรียนวัดรังสิตาวาส สังกัดสำนักงานเขตพื้นที่การศึกษาประถมศึกษายะลา เขต 1 <strong class="school-blue">(G10)</strong>
                                    </li>
                                </ul>
                            </div>
                        </div>

                    </div>
                    <div class="info-card modern-card">
                        <div class="display:flex; flex-direction:column; width:100%;">
                            <div style="display:flex; align-items:center; margin-bottom:0.5rem;">
                                <i
                                    class="fas fa-piggy-bank info-icon"></i>นิทรรศการสถานศึกษาที่ดําเนินการระบบธนาคารหน่วยกิต
                                จัดทําหลักสูตรเชื่อมโยง
                            </div>
                            <div style="margin-bottom:0.5rem;">
                                <strong>รายชื่อสถานศึกษา / สังกัด</strong>
                                <ul
                                    style="margin-top:0.3rem; margin-bottom:0.3rem; padding-left:1.2em; font-size:0.98em;">
                                    <li>วิทยาลัยการอาชีพนครยะลา สำนักงานอาชีวศึกษาจังหวัดยะลา <strong class="school-blue">(G3)</strong>
                                    </li>
                                    <li>โรงเรียนคุรุชนพัฒนา สังกัดสำนักงานเขตพื้นที่การศึกษาประถมศึกษายะลา เขต 1 <strong class="school-blue">(G4)</strong>
                                    </li>
                                    <li>โรงเรียนบ้านบาละ <strong class="school-blue">(G5)</strong>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="info-card modern-card">
                        <div class="display:flex; flex-direction:column; width:100%;">
                            <div style="display:flex; align-items:center; margin-bottom:0.5rem;">
                                <i class="fas fa-campground info-icon"></i>โรงเรียนดีวิถีลูกเสือ
                            </div>
                            <div style="margin-bottom:0.5rem;">
                                <strong>รายชื่อสถานศึกษา / สังกัด</strong>
                                <ul
                                    style="margin-top:0.3rem; margin-bottom:0.3rem; padding-left:1.2em; font-size:0.98em;">
                                    <li>วิทยาลัยการอาขีพรามัน สำนักงานอาชีวศึกษาจังหวัดยะลา <strong class="school-blue">(G1)</strong></li>
                                    <li>โรงเรียนศรีพัฒนาราม สังกัดสำนักงานเขตพื้นที่การศึกษาประถมศึกษายะลา เขต 1 <strong class="school-blue">(G2)</strong>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="info-card modern-card">
                        <div class="display:flex; flex-direction:column; width:100%;">
                            <div style="display:flex; align-items:center; margin-bottom:0.5rem;">
                                <i
                                    class="fas fa-rocket info-icon"></i>การจัดแสดงผลงานนิทรรศการนวัตกรรมการศึกษาจังหวัดยะลา
                            </div>
                            <div style="margin-bottom:0.5rem;">
                                <strong>รายชื่อสถานศึกษา / สังกัด</strong>
                                <ul
                                    style="margin-top:0.3rem; margin-bottom:0.3rem; padding-left:1.2em; font-size:0.98em;">
                                    <li>โรงเรียนนําร่องพื้นที่นวัตกรรมจังหวัดยะลา 42 โรง <strong
                                            class="school-blue">(S1 - S42)</strong></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="info-card modern-card">
                        <div class="display:flex; flex-direction:column; width:100%;">
                            <div style="display:flex; align-items:center; margin-bottom:0.5rem;">
                                <i class="fas fa-hand-holding-heart info-icon"></i>นิทรรศการ ธรรมนูญ 9 ดี (กม.น้อย)
                            </div>
                            <div style="margin-bottom:0.5rem;">
                                <strong>รายชื่อสถานศึกษา / สังกัด</strong>
                                <ul
                                    style="margin-top:0.3rem; margin-bottom:0.3rem; padding-left:1.2em; font-size:0.98em;">
                                    <li>โรงเรียนรามันห์ศิริวิทย์ สำนักงานเขตพื้นที่การศึกษามัธยมศึกษายะลา <strong class="school-blue">(G13)</strong>
                                    </li>
                                    <li>โรงเรียนบ้านยะหา สังกัดสำนักงานเขตพื้นที่การศึกษาประถมศึกษายะลา เขต 2 <strong class="school-blue">(G14)</strong></li>
                                    <li>ศูนย์ขับเคลื่อน ฯ (ส่วนหน้า) <strong class="school-blue">(G15)</strong></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="info-card modern-card">
                        <div class="display:flex; flex-direction:column; width:100%;">
                            <div style="display:flex; align-items:center; margin-bottom:0.5rem;">
                                <i class="fas fa-coins info-icon"></i>โกโก้โมเดล
                            </div>
                            <div style="margin-bottom:0.5rem;">
                                <strong>รายชื่อสถานศึกษา / สังกัด</strong>
                                <ul
                                    style="margin-top:0.3rem; margin-bottom:0.3rem; padding-left:1.2em; font-size:0.98em;">
                                    <li>โรงเรียนลุกมานนูลฮากีม สำนักงานการศึกษาเอกชนจังหวัดยะลา <strong class="school-blue">(G19)</strong>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-card modern-card">
                        <div style="display:flex; flex-direction:column; width:100%;">
                            <div style="display:flex; align-items:center; margin-bottom:0.5rem;">
                                <i class="fas fa-chart-line info-icon"></i>
                                <strong
                                    style="font-size:1.1em;">นิทรรศการการขับเคลื่อนการพัฒนาการศึกษาตามแนวทาง<br>การประเมินสมรรถนะนักเรียนมาตรฐานสากล
                                    (PISA)</strong>
                            </div>
                            <strong>ผลการคัดเลือกผลงานที่เป็นแนวปฏิบัติที่เป็นเลิศ (Best Practice) <br>
                                ในการขับเคลื่อนการพัฒนาการศึกษาตามแนวทางการประเมินสมรรถนะนักเรียนมาตรฐานสากล
                                (PISA)</strong>
                            <strong>รายชื่อสถานศึกษา / สังกัด</strong>
                            <ul style="margin-top:0.3rem; margin-bottom:0.3rem; padding-left:1.2em; font-size:0.98em;">
                                <li>โรงเรียนบ้านราโมง สังกัดสำนักงานเขตพื้นที่การศึกษาประถมศึกษายะลา เขต 3 <strong class="school-blue">(G16)</strong></li>
                                <li>โรงเรียนวัดรังสิตาวาส สังกัดสำนักงานเขตพื้นที่การศึกษาประถมศึกษายะลา เขต 1 <strong class="school-blue">(G17)</strong>
                                </li>
                                <li>โรงเรียนบังนังสตาวิทยา สำนักงานเขตพื้นที่การศึกษามัธยมศึกษายะลา <strong class="school-blue">(G18)</strong></li>
                            </ul>
                        </div>
                    </div>
                    <div class="info-card modern-card">
                        <div class="display:flex; flex-direction:column; width:100%;">
                            <div style="display:flex; align-items:center; margin-bottom:0.5rem;">
                                <i class="fas fa-leaf info-icon"></i>
                                นิทรรศการสวนพฤกษศาสตร์โรงเรียนในโครงการอนุรักษ์พันธุกรรมพืช<br>อันเนื่องมาจากพระราชดําริสมเด็จพระเทพรัตนราชสุดาฯ
                                สยามบรมราชกุมาร
                            </div>
                            <div style="margin-bottom:0.5rem;">
                                <strong>รายชื่อสถานศึกษา / สังกัด</strong>
                                <ul
                                    style="margin-top:0.3rem; margin-bottom:0.3rem; padding-left:1.2em; font-size:0.98em;">
                                    <li>โรงเรียน ตชด.เฉลิมพระเกียรติฯ ตชด.44 <strong class="school-blue">(G24)</strong>
                                    </li>
                                    <li>โรงเรียนพัฒนาบาลอ สังกัดสำนักงานเขตพื้นที่การศึกษาประถมศึกษายะลา เขต 1 <strong class="school-blue">(G25)</strong>
                                    </li>
                                    <li>โรงเรียนลําพะยาประชานุเคราะห์ องค์การบริหารส่วนจังหวัดยะลา <strong
                                            class="school-blue">(G26)</strong></li>
                                    <li>วิทยาลัยการอาชีพเบตง สำนักงานอาชีวศึกษาจังหวัดยะลา <strong class="school-blue">(G27)</strong></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="info-card modern-card">
                        <div class="display:flex; flex-direction:column; width:100%;">
                            <div style="display:flex; align-items:center; margin-bottom:0.5rem;">
                                <i class="fas fa-university info-icon"></i>ลดความเหลื่อมล้ําทางการศึกษา /
                                โอกาสทางการศึกษา
                            </div>
                            <div style="margin-bottom:0.5rem;">
                                <strong>รายชื่อสถานศึกษา / สังกัด</strong>
                                <ul
                                    style="margin-top:0.3rem; margin-bottom:0.3rem; padding-left:1.2em; font-size:0.98em;">
                                    <li>ศูนย์การศึกษาพิเศษ เขตการศึกษา 2 ยะลา <strong
                                            class="school-blue">(โซนหน้าหอประชุม (ด้านซ้าย))</strong></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="info-card modern-card">
                        <div class="display:flex; flex-direction:column; width:100%;">
                            <div style="display:flex; align-items:center; margin-bottom:0.5rem;">
                                <i class="fas fa-ban info-icon"></i>Yala Zero Dropout (YZD)
                            </div>
                            <div style="margin-bottom:0.5rem;">
                                <strong>รายชื่อสถานศึกษา / สังกัด</strong>
                                <ul
                                    style="margin-top:0.3rem; margin-bottom:0.3rem; padding-left:1.2em; font-size:0.98em;">
                                    <li>สํานักงานส่งเสริมการเรียนรู้ประจําจังหวัดยะลา <strong
                                            class="school-blue">(โซนหน้าหอประชุม (ด้านซ้าย))</strong></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="info-card modern-card">
                        <div class="display:flex; flex-direction:column; width:100%;">
                            <div style="display:flex; align-items:center; margin-bottom:0.5rem;">
                                <i class="fas fa-tools info-icon"></i>นวัตกรรมโครงงานสิ่งประดิษฐ์
                            </div>
                            <div style="margin-bottom:0.5rem;">
                                <strong>รายชื่อสถานศึกษา / สังกัด</strong>
                                <ul
                                    style="margin-top:0.3rem; margin-bottom:0.3rem; padding-left:1.2em; font-size:0.98em;">
                                    <li>วิทยาลัยเทคนิคยะลา สำนักงานอาชีวศึกษาจังหวัดยะลา <strong class="school-blue">(G21)</strong></li>
                                    <li>วิทยาลัยอาชีวศึกษาจังหวัดยะลา สำนักงานอาชีวศึกษาจังหวัดยะลา <strong
                                            class="school-blue">(G22)</strong></li>
                                    <li>วิทยาลัยการอาชีพนครยะลา สำนักงานอาชีวศึกษาจังหวัดยะลา <strong class="school-blue">(G23)</strong>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="info-card modern-card">
                        <div class="display:flex; flex-direction:column; width:100%;">
                            <div style="display:flex; align-items:center; margin-bottom:0.5rem;">
                                <i class="fas fa-book-reader info-icon"></i>การเรียนรู้หนังสือ
                                / Spider Net
                            </div>
                            <div style="margin-bottom:0.5rem;">
                                <strong>รายชื่อสถานศึกษา / สังกัด</strong>
                                <ul
                                    style="margin-top:0.3rem; margin-bottom:0.3rem; padding-left:1.2em; font-size:0.98em;">
                                    <li>มหาวิทยาลัยราชภัฏยะลา <strong class="school-blue">(โซนหน้าหอประชุม
                                            (ด้านขวา))</strong></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="info-card modern-card">
                        <div class="display:flex; flex-direction:column; width:100%;">
                            <div style="display:flex; align-items:center; margin-bottom:0.5rem;">
                                <i class="fas fa-coins info-icon"></i>ประชาสัมพันธ์ภารกิจ สกสค. จังหวัดยะลา
                            </div>
                            <div style="margin-bottom:0.5rem;">
                                <strong>รายชื่อสถานศึกษา / สังกัด</strong>
                                <ul
                                    style="margin-top:0.3rem; margin-bottom:0.3rem; padding-left:1.2em; font-size:0.98em;">
                                    <li>สํานักงาน สกสค. จังหวัดยะลา <strong class="school-blue">(G12)</strong></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="info-card modern-card">
                        <div class="display:flex; flex-direction:column; width:100%;">
                            <div style="display:flex; align-items:center; margin-bottom:0.5rem;">
                                <i class="fas fa-coins info-icon"></i>การขับเคลื่อนหลักสูตรต้านทุจริตศึกษา
                            </div>
                            <div style="margin-bottom:0.5rem;">
                                <strong>รายชื่อสถานศึกษา / สังกัด</strong>
                                <ul
                                    style="margin-top:0.3rem; margin-bottom:0.3rem; padding-left:1.2em; font-size:0.98em;">
                                    <li>โรงเรียนมานะศึกษา สำนักงานการศึกษาเอกชนจังหวัดยะลา <strong class="school-blue">(G11)</strong></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="info-card modern-card">
                        <div class="display:flex; flex-direction:column; width:100%;">
                            <div style="display:flex; align-items:center; margin-bottom:0.5rem;">
                                <i class="fas fa-coins info-icon"></i>Learn to Earn
                            </div>
                            <div style="margin-bottom:0.5rem;">
                                <strong>รายชื่อสถานศึกษา / สังกัด</strong>
                                <ul
                                    style="margin-top:0.3rem; margin-bottom:0.3rem; padding-left:1.2em; font-size:0.98em;">
                                    <li>โรงเรียนศรีบางลางวิทยานุสรณ์ สำนักงานการศึกษาเอกชนจังหวัดยะลา <strong
                                            class="school-blue">(G20)</strong></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </section>

    <section id="floor-plan" style="padding: 3rem 0; background: #f7fff7;">
        <div class="container">
            <h2 class="section-title mb-4" style="font-size:1.7rem; color:var(--primary-green); font-weight:700;">
                <i class="fas fa-map" style="color:var(--accent-gold); margin-right:8px; font-size:1.5rem;"></i>
                แผนผังพื้นที่จัดงาน (Floor Plan)
            </h2>
            <div class="row g-4">
                <!-- Floor Plan Images -->
                <div class="col-12">
                    <h3 style="font-size:1.15rem; color:var(--primary-green); font-weight:600; margin-bottom:1rem;"><i
                            class="fas fa-th-large" style="color:var(--accent-gold); margin-right:6px;"></i> Floor
                        Plan</h3>
                </div>
                <div class="col-12 mb-4">
                    <img src="{{ asset('assets/images/yala-edu-fest/floor-plans/floor-plan/floor-plan-1.jpg') }}"
                        alt="Floor Plan 1" class="img-fluid rounded shadow-sm w-100"
                        style="object-fit:contain; width:100%;">
                </div>
                <div class="col-12 mb-4">
                    <img src="{{ asset('assets/images/yala-edu-fest/floor-plans/floor-plan/floor-plan-2.jpg') }}"
                        alt="Floor Plan 2" class="img-fluid rounded shadow-sm w-100"
                        style="object-fit:contain; width:100%;">
                </div>
                <!-- Layout Info Images -->
                <div class="col-12 mt-4">
                    <h3 style="font-size:1.15rem; color:var(--primary-green); font-weight:600; margin-bottom:1rem;"><i
                            class="fas fa-info-circle" style="color:var(--accent-gold); margin-right:6px;"></i> Layout
                        Info</h3>
                </div>
                <div class="col-12 mb-4">
                    <img src="{{ asset('assets/images/yala-edu-fest/floor-plans/layout-info/layout-1.jpg') }}"
                        alt="Layout 1" class="img-fluid rounded shadow-sm w-100"
                        style="object-fit:contain; width:100%;">
                </div>
                <div class="col-12 mb-4">
                    <img src="{{ asset('assets/images/yala-edu-fest/floor-plans/layout-info/layout-2.jpg') }}"
                        alt="Layout 2" class="img-fluid rounded shadow-sm w-100"
                        style="object-fit:contain; width:100%;">
                </div>
            </div>
        </div>
    </section>

    <!-- Competition Results Modal -->
    <div id="competitionModal" class="competition-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title" id="modalTitle">
                    <i class="fas fa-trophy"></i>
                    <span id="modalTitleText">ผลการแข่งขัน</span>
                </h2>
                <button class="close-btn" onclick="closeCompetitionModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body" id="modalBody">
                <!-- Dynamic content will be inserted here -->
            </div>
        </div>
    </div>

    <script>
        // Competition Results Data
        const competitionData = {
            'sdg4-innovation': {
                title: 'นวัตกรรมที่มีผลการดำเนินงานบรรลุเป้าหมายการพัฒนาที่ยั่งยืนด้านการศึกษา (SDG4)',
                icon: 'fas fa-globe-asia',
                results: [{
                        rank: 'ชนะเลิศ',
                        rankClass: 'first-place',
                        icon: 'fas fa-crown',
                        iconClass: '',
                        teamName: 'Green Team',
                        schoolName: 'โรงเรียนแสงทิพย์วิทยา',
                        affiliation: 'สังกัดสำนักงานศึกษาเอกชนจังหวัดยะลา'
                    },
                    {
                        rank: 'รองชนะเลิศอันดับ 1',
                        rankClass: 'second-place',
                        icon: 'fas fa-medal',
                        iconClass: 'second',
                        teamName: 'วัยซน วัยซ่าส์',
                        schoolName: 'วิทยาลัยอาชีวศึกษาผดุงประชายะลา',
                        affiliation: 'สังกัดสำนักงานอาชีวศึกษาจังหวัดยะลา'
                    },
                    {
                        rank: 'รองชนะเลิศอันดับ 2',
                        rankClass: 'third-place',
                        icon: 'fas fa-award',
                        iconClass: 'third',
                        teamName: 'We are ผดุงประชา',
                        schoolName: 'วิทยาลัยอาชีวศึกษาผดุงประชายะลา',
                        affiliation: 'สังกัดสำนักงานอาชีวศึกษาจังหวัดยะลา'
                    }
                ]
            },
            'pisa': {
                title: 'การขับเคลื่อนการพัฒนาการศึกษาตามแนวทางการประเมินสมรรถนะนักเรียนมาตรฐานสากล (PISA)',
                icon: 'fas fa-chart-line',
                results: [{
                        rank: 'ชนะเลิศ (ระดับดีเยี่ยม)',
                        rankClass: 'first-place',
                        icon: 'fas fa-crown',
                        iconClass: '',
                        schoolName: 'โรงเรียนบ้านราโมง',
                        affiliation: 'สังกัดสำนักงานเขตพื้นที่การศึกษาประถมศึกษา เขต 3'
                    },
                    {
                        rank: 'รองชนะเลิศอันดับ 1 (ระดับดีเยี่ยม)',
                        rankClass: 'second-place',
                        icon: 'fas fa-medal',
                        iconClass: 'second',
                        schoolName: 'โรงเรียนวัดรังสิตาวาส',
                        affiliation: 'สังกัดสำนักงานเขตพื้นที่การศึกษาประถมศึกษายะลา เขต 1'
                    },
                    {
                        rank: 'รองชนะเลิศอันดับ 2 (ระดับดีมาก)',
                        rankClass: 'third-place',
                        icon: 'fas fa-award',
                        iconClass: 'third',
                        schoolName: 'โรงเรียนลันนังสตาวิทยา',
                        affiliation: 'สังกัดสำนักงานเขตพื้นที่การศึกษามัธยมศึกษายะลา'
                    },
                    {
                        rank: 'รางวัลชมเชย (ระดับดี)',
                        rankClass: 'honorable-mention',
                        icon: 'fas fa-certificate',
                        iconClass: 'mention',
                        schoolName: 'วิทยาลัยอาชีวศึกษายะลา',
                        affiliation: 'สังกัดสำนักงานอาชีวศึกษาจังหวัดยะลา'
                    }
                ]
            },
            'guide': {
                title: 'การแข่งขันประกวดมัคคุเทศก์น้อย',
                icon: 'fas fa-child',
                b4_6Results: [{
                        rank: 'ชนะเลิศ',
                        rankClass: 'first-place',
                        icon: 'fas fa-crown',
                        iconClass: '',
                        schoolName: 'เด็กหญิงนูรซาฟาร์ จารู',
                        affiliation: 'โรงเรียนสาธิตมหาวิทยาลัยราชภัฏยะลา'
                    },
                    {
                        rank: 'รองชนะเลิศอันดับ 1',
                        rankClass: 'second-place',
                        icon: 'fas fa-medal',
                        iconClass: 'second',
                        schoolName: 'เด็กหญิงอัฟฟา เจาะกลาดี',
                        affiliation: 'โรงเรียนบ้านใหม่ (วันครู 2503)'
                    },
                    {
                        rank: 'รองชนะเลิศอันดับ 2',
                        rankClass: 'third-place',
                        icon: 'fas fa-award',
                        iconClass: 'third',
                        schoolName: 'เด็กหญิงนูรูลอิหซาน ยูโซะ',
                        affiliation: 'โรงเรียนบันนังสตาอินทรฉัตรฯ'
                    }
                ],
                m1_3Results: [{
                        rank: 'ชนะเลิศ',
                        rankClass: 'first-place',
                        icon: 'fas fa-crown',
                        iconClass: '',
                        schoolName: 'เด็กหญิงปิยเนตร ขวัญใจ',
                        affiliation: 'โรงเรียนไทยรัฐวิทยา 94 (บ้านน้ำร้อน)'
                    },
                    {
                        rank: 'รองชนะเลิศอันดับ 1',
                        rankClass: 'second-place',
                        icon: 'fas fa-medal',
                        iconClass: 'second',
                        schoolName: 'เด็กหญิงอมรรัตน์  อินทรสุวรรณ',
                        affiliation: 'โรงเรียนไทยรัฐวิทยา 94 (บ้านน้ำร้อน)'
                    },
                    {
                        rank: 'รองชนะเลิศอันดับ 2',
                        rankClass: 'third-place',
                        icon: 'fas fa-award',
                        iconClass: 'third',
                        schoolName: 'เด็กหญิงมัลลิกา จันทร์ทอง',
                        affiliation: 'โรงเรียนสาธิตมหาวิทยาลัยราชภัฏยะลา'
                    }
                ],
                m4_6Results: [{
                        rank: 'ชนะเลิศ',
                        rankClass: 'first-place',
                        icon: 'fas fa-crown',
                        iconClass: '',
                        schoolName: 'นางสาวเมลดา  กองสิน',
                        affiliation: 'วิทยาลัยการอาชีพนครยะลา'
                    },
                    {
                        rank: 'รองชนะเลิศอันดับ 1',
                        rankClass: 'second-place',
                        icon: 'fas fa-medal',
                        iconClass: 'second',
                        schoolName: 'นางสาวณัสรา  ซาและ',
                        affiliation: 'วิทยาลัยอาชีวศึกษาผดุงประชายะลา'
                    },
                    {
                        rank: 'รองชนะเลิศอันดับ 2',
                        rankClass: 'third-place',
                        icon: 'fas fa-award',
                        iconClass: 'third',
                        schoolName: 'นางสาวนาดา  ชิมา',
                        affiliation: 'วิทยาลัยอาชีวศึกษาผดุงประชายะลา'
                    }
                ]
            },
            'soft-power': {
                title: 'ผลงานยอดเยี่ยมด้านการพัฒนานวัตกรรม Soft Power',
                icon: 'fas fa-child',
                results: [{
                        rank: 'ชนะเลิศ',
                        rankClass: 'first-place',
                        icon: 'fas fa-crown',
                        iconClass: '',
                        schoolName: 'โรงเรียบ้านบาละ',
                        affiliation: 'สังกัดสำนักงานเขตพื้นที่การศึกษาประถมศึกษายะลา เขต 2'
                    },
                    {
                        rank: 'รองชนะเลิศอันดับ 1',
                        rankClass: 'second-place',
                        icon: 'fas fa-medal',
                        iconClass: 'second',
                        schoolName: 'โรงเรียนสันติศาสตร์วิทยา',
                        affiliation: 'สำนักงานการศึกษาเอกชนจังหวัดยะลา'
                    },
                    {
                        rank: 'รองชนะเลิศอันดับ 2',
                        rankClass: 'third-place',
                        icon: 'fas fa-award',
                        iconClass: 'third',
                        schoolName: 'โรงเรียนญัณญาร์วิทย์',
                        affiliation: 'สำนักงานการศึกษาเอกชนจังหวัดยะลา'
                    }
                ]
            },
            'song': {
                title: 'การประกวดร้องเพลงพระราชนิพนธ์',
                icon: 'fas fa-microphone',
                batomResults: [{
                        rank: 'ชนะเลิศ',
                        rankClass: 'first-place',
                        icon: 'fas fa-crown',
                        iconClass: '',
                        schoolName: 'เด็กหญิงณัฏฐณิชา สุขสวัสดิ์',
                        affiliation: 'โรงเรียนอนุบาลยะลา สังกัดสำนักงานเขตพื้นที่การศึกษาประถมศึกษายะลา เขต 1'
                    },
                    {
                        rank: 'รองชนะเลิศอันดับ 1',
                        rankClass: 'second-place',
                        icon: 'fas fa-medal',
                        iconClass: 'second',
                        schoolName: 'เด็กหญิงนาอีมะห์ หะเร็ม',
                        affiliation: 'โรงเรียนบ้านตะโละหะลอ สังกัดสำนักงานเขตพื้นที่การศึกษาประถมศึกษายะลา เขต 1'
                    },
                    {
                        rank: 'รองชนะเลิศอันดับ 2',
                        rankClass: 'third-place',
                        icon: 'fas fa-award',
                        iconClass: 'third',
                        schoolName: 'เด็กหญิงนาตาชา เส็นหลีหมีน',
                        affiliation: 'โรงเรียนสาธิตมหาวิทยาลัยราชภัฏยะลา'
                    },
                    {
                        rank: 'รางวัลชมเชย',
                        rankClass: 'honorable-mention',
                        icon: 'fas fa-certificate',
                        iconClass: 'mention',
                        schoolName: 'เด็กหญิงจิตติมา มากเกลี้ยง',
                        affiliation: 'โรงเรียนนิคมสร้างตนเองพัฒนาภาคใต้ 1 สังกัดสำนักงานเขตพื้นที่การศึกษาประถมศึกษายะลา เขต 2'
                    },
                    {
                        rank: 'รางวัลชมเชย',
                        rankClass: 'honorable-mention',
                        icon: 'fas fa-certificate',
                        iconClass: 'mention',
                        schoolName: 'เด็กหญิงนาซูวา  ยาละแน',
                        affiliation: 'โรงเรียนเทศบาล 2 (บ้านมลายูบางกอก)'
                    },
                    {
                        rank: 'รางวัลชมเชย',
                        rankClass: 'honorable-mention',
                        icon: 'fas fa-certificate',
                        iconClass: 'mention',
                        schoolName: 'เด็กหญิงหญิงมธุสร  เกตุแก้ว',
                        affiliation: 'โรงเรียนบ้านบาละ สังกัดสำนักงานเขตพื้นที่การศึกษาประถมศึกษายะลา เขต 2'
                    }
                ],
                muttayomResults: [{
                        rank: 'ชนะเลิศ',
                        rankClass: 'first-place',
                        icon: 'fas fa-crown',
                        iconClass: '',
                        schoolName: 'เด็กชายธนศักดิ์ คลองรั้ว',
                        affiliation: 'โรงเรียนเทศบาล 2 (บ้านมลายูบางกอก)'
                    },
                    {
                        rank: 'รองชนะเลิศอันดับ 1',
                        rankClass: 'second-place',
                        icon: 'fas fa-medal',
                        iconClass: 'second',
                        schoolName: 'นางสาวอภิสรา อินทบูลย์',
                        affiliation: 'โรงเรียนลำพะยาประชานุเคราะห์'
                    },
                    {
                        rank: 'รองชนะเลิศอันดับ 2',
                        rankClass: 'third-place',
                        icon: 'fas fa-award',
                        iconClass: 'third',
                        schoolName: 'นางสาวนูรีฟาณ์ ยูโซะ',
                        affiliation: 'โรงเรียนกาบังพิทยาคม'
                    },
                    {
                        rank: 'รางวัลชมเชย',
                        rankClass: 'honorable-mention',
                        icon: 'fas fa-certificate',
                        iconClass: 'mention',
                        schoolName: 'นายอาลิฟ ขำอนันต์',
                        affiliation: 'โรงเรียนราชประชานุเคราะห์ 41 จ.ยะลา'
                    },
                    {
                        rank: 'รางวัลชมเชย',
                        rankClass: 'honorable-mention',
                        icon: 'fas fa-certificate',
                        iconClass: 'mention',
                        schoolName: 'เด็กหญิงนูรอิลฮัม อาแซ',
                        affiliation: 'โรงเรียนบ้านตะโละหะลอ สังกัดสำนักงานเขตพื้นที่การศึกษาประถมศึกษายะลา เขต 1'
                    },
                    {
                        rank: 'รางวัลชมเชย',
                        rankClass: 'honorable-mention',
                        icon: 'fas fa-certificate',
                        iconClass: 'mention',
                        schoolName: 'เด็กหญิงมัลลิกา จันทร์ทอง',
                        affiliation: 'โรงเรียนสาธิตมหาวิทยาลัยราชภัฏยะลา'
                    }
                ],
            },
            'storytelling': {
                title: 'การแข่งขันเล่านิทานประกอบท่าทาง',
                icon: 'fas fa-book-open',
                results: [{
                        rank: 'ชนะเลิศ',
                        rankClass: 'first-place',
                        icon: 'fas fa-crown',
                        iconClass: '',
                        schoolName: 'เด็กหญิงธนัตพร มะสุวรรณ',
                        affiliation: 'โรงเรียนเทศบาล 4 (ธนวิถี) เทศบาลนครยะลา'
                    },
                    {
                        rank: 'รองชนะเลิศอันดับ 1',
                        rankClass: 'second-place',
                        icon: 'fas fa-medal',
                        iconClass: 'second',
                        schoolName: 'เด็กหญิงนิอูมัยรา มูเด็ง',
                        affiliation: 'โรงเรียนอนุบาลเบตง (สุภาพอนุสรณ์) สังกัดสำนักงานเขตพื้นที่การศึกษาประถมศึกษายะลา เขต 3'
                    },
                    {
                        rank: 'รองชนะเลิศอันดับ 2',
                        rankClass: 'third-place',
                        icon: 'fas fa-award',
                        iconClass: 'third',
                        schoolName: 'เด็กหญิงดาลิชฌา ลีหมะ',
                        affiliation: 'โรงเรียนเทศบาล 4 (ธนวิถี) เทศบาลนครยะลา'
                    },
                    {
                        rank: 'รองชนะเลิศอันดับ 2',
                        rankClass: 'third-place',
                        icon: 'fas fa-award',
                        iconClass: 'third',
                        schoolName: 'เด็กหญิงนิซาร่าห์ ดาราไกย',
                        affiliation: 'โรงเรียนศรีบางลางนุสรณ์ สำนักงานการศึกษาเอกชนจังหวัดยะลา'
                    },
                    {
                        rank: 'รางวัลชมเชย',
                        rankClass: 'honorable-mention',
                        icon: 'fas fa-certificate',
                        iconClass: 'mention',
                        schoolName: 'เด็กหญิงนัจญมี การี',
                        affiliation: 'โรงเรียนบ้านพอแม็ง สังกัดสำนักงานเขตพื้นที่การศึกษาประถมศึกษายะลา เขต 1'
                    },
                    {
                        rank: 'รางวัลชมเชย',
                        rankClass: 'honorable-mention',
                        icon: 'fas fa-certificate',
                        iconClass: 'mention',
                        schoolName: 'เด็กหญิงณัฏฐณิชา ศรีเจริญ',
                        affiliation: 'โรงเรียนสาธิตมหาวิทยาลัยราชภัฏยะลา'
                    }
                ],

            },
            'early-childhood': {
                title: 'การคัดเลือก Best Practice การพัฒนาการจัดการศึกษาเด็กปฐมวัย',
                icon: 'fas fa-lightbulb',
                bossResults: [{
                        rank: 'ชนะเลิศ',
                        rankClass: 'first-place',
                        icon: 'fas fa-crown',
                        iconClass: '',
                        schoolName: 'โรงเรียนบ้านตะโละหะลอ',
                        affiliation: 'สังกัดสำนักงานเขตพื้นที่การศึกษาประถมศึกษายะลา เขต 1'
                    },
                    {
                        rank: 'รองชนะเลิศอันดับ 1',
                        rankClass: 'second-place',
                        icon: 'fas fa-medal',
                        iconClass: 'second',
                        schoolName: 'โรงเรียนอนุบาลเบตง (สุภาพอนุสรณ์)',
                        affiliation: 'สังกัดสำนักงานเขตพื้นที่การศึกษาประถมศึกษายะลา เขต 3'
                    },
                    {
                        rank: 'รองชนะเลิศอันดับ 2',
                        rankClass: 'third-place',
                        icon: 'fas fa-award',
                        iconClass: 'third',
                        schoolName: 'โรงเรียนบ้านวังใหม่ (ประชาอุทิศ 2519)',
                        affiliation: 'สังกัดสำนักงานเขตพื้นที่การศึกษาประถมศึกษายะลา เขต 3'
                    }
                ],
                teacherResults: [{
                        rank: 'ชนะเลิศ',
                        rankClass: 'first-place',
                        icon: 'fas fa-crown',
                        iconClass: '',
                        schoolName: 'นางอามานีย์ ซียง',
                        affiliation: 'โรงเรียนบ้านลูโบ๊ะปันยัง สังกัดสำนักงานเขตพื้นที่การศึกษาประถมศึกษายะลา เขต 2'
                    },
                    {
                        rank: 'รองชนะเลิศอันดับ 1',
                        rankClass: 'second-place',
                        icon: 'fas fa-medal',
                        iconClass: 'second',
                        schoolName: 'นางสาวอาสมี สะอิ',
                        affiliation: 'โรงเรียนบ้าบันนังลูวา สังกัดสำนักงานเขตพื้นที่การศึกษาประถมศึกษายะลา เขต 1'
                    },
                    {
                        rank: 'รองชนะเลิศอันดับ 2',
                        rankClass: 'third-place',
                        icon: 'fas fa-award',
                        iconClass: 'third',
                        schoolName: 'นางสาวจามรี ทวีศรี',
                        affiliation: 'โรงเรียนบ้านอัยเยอร์เวง สังกัดสำนักงานเขตพื้นที่การศึกษาประถมศึกษายะลา เขต 3'
                    },
                    {
                        rank: 'รางวัลชมเชย',
                        rankClass: 'honorable-mention',
                        icon: 'fas fa-certificate',
                        iconClass: 'mention',
                        schoolName: 'นางสาวซูซันซินี โต๊ะแวมะ',
                        affiliation: 'โรงเรียนบ้านป่าหวัง สังกัดสำนักงานเขตพื้นที่การศึกษาประถมศึกษายะลา เขต 2'
                    },
                    {
                        rank: 'รางวัลชมเชย',
                        rankClass: 'honorable-mention',
                        icon: 'fas fa-certificate',
                        iconClass: 'mention',
                        schoolName: 'นางสาววาริญา  เวาะหลง',
                        affiliation: 'โรงเรียนสันติศาสตร์วิทยา สำนักงานการศึกษาเอกชนจังหวัดยะลา'
                    }
                ]
            },
            'stem': {
                title: 'ผลงานยอดเยี่ยมด้านการพัฒนานวัตกรรม การเรียนรู้แบบ STEM Education',
                icon: 'fas fa-atom',
                results: [{
                        rank: 'ชนะเลิศ',
                        rankClass: 'first-place',
                        icon: 'fas fa-crown',
                        iconClass: '',
                        schoolName: 'โรงเรียนบ้านราโมง',
                        affiliation: 'สังกัดสำนักงานเขตพื้นที่การศึกษาประถมศึกษายะลา เขต 3'
                    },
                    {
                        rank: 'รองชนะเลิศอันดับ 1',
                        rankClass: 'second-place',
                        icon: 'fas fa-medal',
                        iconClass: 'second',
                        schoolName: 'โรงเรียนสุขสวัสดิ์วิทยา',
                        affiliation: 'สำนักงานการศึกษาเอกชนจังหวัดยะลา'
                    },
                    {
                        rank: 'รองชนะเลิศอันดับ 2',
                        rankClass: 'third-place',
                        icon: 'fas fa-award',
                        iconClass: 'third',
                        schoolName: 'โรงเรียนบ้านใหม่ (วันครู 2503)',
                        affiliation: 'สังกัดสำนักงานเขตพื้นที่การศึกษาประถมศึกษายะลา เขต 3'
                    },
                    {
                        rank: 'รางวัลชมเชย',
                        rankClass: 'honorable-mention',
                        icon: 'fas fa-certificate',
                        iconClass: 'mention',
                        schoolName: 'โรงเรียนสันติศาสตร์วิทยา',
                        affiliation: 'สำนักงานการศึกษาเอกชนจังหวัดยะลา'
                    },
                    {
                        rank: 'รางวัลชมเชย',
                        rankClass: 'honorable-mention',
                        icon: 'fas fa-certificate',
                        iconClass: 'mention',
                        schoolName: 'โรงเรียนบ้านยะหา',
                        affiliation: 'สังกัดสำนักงานเขตพื้นที่การศึกษาประถมศึกษายะลา เขต 2'
                    }
                ]
            },
            'coaching': {
                title: 'ผลงานยอดเยี่ยมด้านการพัฒนานวัตกรรม ระบบแนะแนวทางสำหรับผู้เรียน (Coaching)',
                icon: 'fas fa-user-cog',
                results: [{
                        rank: 'ชนะเลิศ',
                        rankClass: 'first-place',
                        icon: 'fas fa-crown',
                        iconClass: '',
                        schoolName: 'โรงเรียนสุขสวัสดิ์วิทยา',
                        affiliation: 'สำนักงานการศึกษาเอกชนจังหวัดยะลา'
                    }
                ]
            },
            'sdg4-school': {
                title: 'สถานศึกษาที่มีผลการดำเนินงานบรรลุเป้าหมายการพัฒนาที่ยั่งยืนด้านการศึกษา (SDG4)',
                icon: 'fas fa-school',
                results: [{
                        rank: 'ชนะเลิศ',
                        rankClass: 'first-place',
                        icon: 'fas fa-crown',
                        iconClass: '',
                        schoolName: 'โรงเรียนบ้านปอเยาะ',
                        affiliation: 'สังกัดสำนักงานเขตพื้นที่การศึกษาประถมศึกษายะลา เขต 1'
                    },
                    {
                        rank: 'รองชนะเลิศอันดับ 1',
                        rankClass: 'second-place',
                        icon: 'fas fa-medal',
                        iconClass: 'second',
                        schoolName: 'โรงเรียนบ้านรามัน',
                        affiliation: 'สังกัดสำนักงานเขตพื้นที่การศึกษาประถมศึกษายะลา เขต 1'
                    },
                    {
                        rank: 'รองชนะเลิศอันดับ 2',
                        rankClass: 'third-place',
                        icon: 'fas fa-award',
                        iconClass: 'third',
                        schoolName: 'โรงเรียนเบetong "วีระราษฎร์ประสาน"',
                        affiliation: 'สังกัดสำนักงานเขตพื้นที่การศึกษามัธยมศึกษายะลา'
                    }
                ]
            }
        };

        // Modal Functions
        function openCompetitionModal(competitionType) {
            const modal = document.getElementById('competitionModal');
            const modalTitle = document.getElementById('modalTitleText');
            const modalBody = document.getElementById('modalBody');

            const data = competitionData[competitionType];
            if (!data) return;

            modalTitle.textContent = data.title;
            modalBody.innerHTML = generateModalContent(data);

            modal.classList.add('show');
            document.body.style.overflow = 'hidden';
        }

        function closeCompetitionModal() {
            const modal = document.getElementById('competitionModal');
            modal.classList.remove('show');
            document.body.style.overflow = 'auto';
        }

        function generateModalContent(data) {
            let content = '';

            // Student/Team Results
            if (data.results && data.results.length > 0) {
                content += `
                    <div class="award-section">
                        <h3 class="award-title">
                            <i class="fas fa-users"></i>
                            ผลการแข่งขันระดับนักเรียน/ทีม
                        </h3>
                `;

                data.results.forEach(result => {
                    content += `
                        <div class="award-card ${result.rankClass}">
                            <div class="award-rank">
                                <i class="${result.icon} award-icon ${result.iconClass}"></i>
                                <span class="rank-badge ${result.iconClass}">${result.rank}</span>
                            </div>
                            <div class="school-info">
                                ${result.teamName ? `<div class="team-name">ทีม: ${result.teamName}</div>` : ''}
                                <div class="school-name">${result.schoolName}</div>
                                <div class="school-affiliation">${result.affiliation}</div>
                            </div>
                        </div>
                    `;
                });

                content += '</div>';
            }

            // School Results
            if (data.schoolResults && data.schoolResults.length > 0) {
                content += `
                    <div class="award-section">
                        <h3 class="award-title">
                            <i class="fas fa-school"></i>
                            ผลการแข่งขันระดับสถานศึกษา
                        </h3>
                `;

                data.schoolResults.forEach(result => {
                    content += `
                        <div class="award-card ${result.rankClass}">
                            <div class="award-rank">
                                <i class="${result.icon} award-icon ${result.iconClass}"></i>
                                <span class="rank-badge ${result.iconClass}">${result.rank}</span>
                            </div>
                            <div class="school-info">
                                <div class="school-name">${result.schoolName}</div>
                                <div class="school-affiliation">${result.affiliation}</div>
                            </div>
                        </div>
                    `;
                });

                content += '</div>';
            }

            if (data.batomResults && data.batomResults.length > 0) {
                content += `
                    <div class="award-section">
                        <h3 class="award-title">
                            <i class="fas fa-school"></i>
                            ผลการแข่งขันระดับประถมศึกษา
                        </h3>
                `;

                data.batomResults.forEach(result => {
                    content += `
                        <div class="award-card ${result.rankClass}">
                            <div class="award-rank">
                                <i class="${result.icon} award-icon ${result.iconClass}"></i>
                                <span class="rank-badge ${result.iconClass}">${result.rank}</span>
                            </div>
                            <div class="school-info">
                                <div class="school-name">${result.schoolName}</div>
                                <div class="school-affiliation">${result.affiliation}</div>
                            </div>
                        </div>
                    `;
                });

                content += '</div>';
            }

            if (data.muttayomResults && data.muttayomResults.length > 0) {
                content += `
                    <div class="award-section">
                        <h3 class="award-title">
                            <i class="fas fa-school"></i>
                            ผลการแข่งขันระดับมัธยมศึกษา
                        </h3>
                `;

                data.muttayomResults.forEach(result => {
                    content += `
                        <div class="award-card ${result.rankClass}">
                            <div class="award-rank">
                                <i class="${result.icon} award-icon ${result.iconClass}"></i>
                                <span class="rank-badge ${result.iconClass}">${result.rank}</span>
                            </div>
                            <div class="school-info">
                                <div class="school-name">${result.schoolName}</div>
                                <div class="school-affiliation">${result.affiliation}</div>
                            </div>
                        </div>
                    `;
                });

                content += '</div>';
            }

            if (data.bossResults && data.bossResults.length > 0) {
                content += `
                    <div class="award-section">
                        <h3 class="award-title">
                            <i class="fas fa-school"></i>
                            ผลการแข่งขันประเภทผู้บริหาร
                        </h3>
                `;

                data.bossResults.forEach(result => {
                    content += `
                        <div class="award-card ${result.rankClass}">
                            <div class="award-rank">
                                <i class="${result.icon} award-icon ${result.iconClass}"></i>
                                <span class="rank-badge ${result.iconClass}">${result.rank}</span>
                            </div>
                            <div class="school-info">
                                <div class="school-name">${result.schoolName}</div>
                                <div class="school-affiliation">${result.affiliation}</div>
                            </div>
                        </div>
                    `;
                });

                content += '</div>';
            }
            if (data.teacherResults && data.teacherResults.length > 0) {
                content += `
                    <div class="award-section">
                        <h3 class="award-title">
                            <i class="fas fa-school"></i>
                            ผลการแข่งขันประเภทครูผู้สอน
                        </h3>
                `;

                data.teacherResults.forEach(result => {
                    content += `
                        <div class="award-card ${result.rankClass}">
                            <div class="award-rank">
                                <i class="${result.icon} award-icon ${result.iconClass}"></i>
                                <span class="rank-badge ${result.iconClass}">${result.rank}</span>
                            </div>
                            <div class="school-info">
                                <div class="school-name">${result.schoolName}</div>
                                <div class="school-affiliation">${result.affiliation}</div>
                            </div>
                        </div>
                    `;
                });

                content += '</div>';
            }

            if (data.b4_6Results && data.b4_6Results.length > 0) {
                content += `
                    <div class="award-section">
                        <h3 class="award-title">
                            <i class="fas fa-school"></i>
                            ผลการแข่งขันระดับชั้น ป. 4 - 6 
                        </h3>
                `;

                data.b4_6Results.forEach(result => {
                    content += `
                        <div class="award-card ${result.rankClass}">
                            <div class="award-rank">
                                <i class="${result.icon} award-icon ${result.iconClass}"></i>
                                <span class="rank-badge ${result.iconClass}">${result.rank}</span>
                            </div>
                            <div class="school-info">
                                <div class="school-name">${result.schoolName}</div>
                                <div class="school-affiliation">${result.affiliation}</div>
                            </div>
                        </div>
                    `;
                });

                content += '</div>';
            }
            if (data.m1_3Results && data.m1_3Results.length > 0) {
                content += `
                    <div class="award-section">
                        <h3 class="award-title">
                            <i class="fas fa-school"></i>
                            ผลการแข่งขันระดับชั้น ม.1 - ม.3 
                        </h3>
                `;

                data.m1_3Results.forEach(result => {
                    content += `
                        <div class="award-card ${result.rankClass}">
                            <div class="award-rank">
                                <i class="${result.icon} award-icon ${result.iconClass}"></i>
                                <span class="rank-badge ${result.iconClass}">${result.rank}</span>
                            </div>
                            <div class="school-info">
                                <div class="school-name">${result.schoolName}</div>
                                <div class="school-affiliation">${result.affiliation}</div>
                            </div>
                        </div>
                    `;
                });

                content += '</div>';
            }
            if (data.m4_6Results && data.m4_6Results.length > 0) {
                content += `
                    <div class="award-section">
                        <h3 class="award-title">
                            <i class="fas fa-school"></i>
                            ผลการแข่งขันระดับ ม. 4 - 6 และ อาชีวศึกษา
                        </h3>
                `;

                data.m4_6Results.forEach(result => {
                    content += `
                        <div class="award-card ${result.rankClass}">
                            <div class="award-rank">
                                <i class="${result.icon} award-icon ${result.iconClass}"></i>
                                <span class="rank-badge ${result.iconClass}">${result.rank}</span>
                            </div>
                            <div class="school-info">
                                <div class="school-name">${result.schoolName}</div>
                                <div class="school-affiliation">${result.affiliation}</div>
                            </div>
                        </div>
                    `;
                });

                content += '</div>';
            }

            return content;
        }

        // Close modal when clicking outside
        document.getElementById('competitionModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeCompetitionModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeCompetitionModal();
            }
        });

        // Page Loading Animation
        document.addEventListener('DOMContentLoaded', function() {
            // Hide page loader after animations complete
            setTimeout(() => {
                const loader = document.getElementById('pageLoader');
                if (loader) {
                    loader.style.opacity = '0';
                    loader.style.visibility = 'hidden';
                }
            }, 3000);

            // Initialize Sticky Navigation
            initStickyNavigation();

            // Initialize Sticky Tabs
            initStickyTabs();
        });

        var tag = document.createElement('script');
        tag.src = "https://www.youtube.com/iframe_api";
        var firstScriptTag = document.getElementsByTagName('script')[0];
        firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

        var player;

        function onYouTubeIframeAPIReady() {
            player = new YT.Player('youtube-video', {
                events: {
                    'onReady': onPlayerReady
                }
            });
        }

        function onPlayerReady(event) {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        player.playVideo();
                    } else {
                        player.pauseVideo();
                    }
                });
            }, {
                threshold: 0.5
            }); // 50% ของ iframe ต้องแสดงใน viewport

            observer.observe(document.getElementById('youtube-video'));
        }

        // Sticky Navigation Functions
        function initStickyNavigation() {
            const stickyNavItems = document.querySelectorAll('.sticky-nav-item');
            const sections = document.querySelectorAll('#hero, #introWrapper, #section_tabs, #infomations, #floor-plan');

            // Smooth scroll for navigation links
            stickyNavItems.forEach(item => {
                item.addEventListener('click', function(e) {
                    e.preventDefault();
                    const targetId = this.getAttribute('href').substring(1);
                    const targetSection = document.getElementById(targetId);

                    if (targetSection) {
                        targetSection.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });

            // Update active navigation item on scroll
            function updateActiveNavItem() {
                let current = '';
                const scrollY = window.pageYOffset;

                sections.forEach(section => {
                    const sectionTop = section.offsetTop;
                    const sectionHeight = section.clientHeight;

                    if (scrollY >= (sectionTop - 200)) {
                        current = section.getAttribute('id');
                    }
                });

                stickyNavItems.forEach(item => {
                    item.classList.remove('active');
                    if (item.getAttribute('data-section') === current) {
                        item.classList.add('active');
                    }
                });
            }

            // Throttled scroll handler for performance
            let ticking = false;

            function onScroll() {
                if (!ticking) {
                    requestAnimationFrame(() => {
                        updateActiveNavItem();
                        ticking = false;
                    });
                    ticking = true;
                }
            }

            window.addEventListener('scroll', onScroll);
            updateActiveNavItem(); // Initial call
        }

        // Sticky Tabs Functionality
        function initStickyTabs() {
            // Pin the intro text section with GSAP if available, otherwise use CSS
            if (typeof gsap !== 'undefined' && gsap.registerPlugin) {
                gsap.registerPlugin(ScrollTrigger);

                ScrollTrigger.create({
                    trigger: ".intro-wrapper",
                    start: "top top",
                    end: "bottom top",
                    pin: ".text-align-center",
                    pinSpacing: false
                });
            }

            // Handling the scroll for the tabs
            function handleTabsScroll() {
                let scrollPosition = window.scrollY;
                let windowHeight = window.innerHeight + 100; // ปรับระยะการเปลี่ยนแท็บ
                let sections = document.querySelectorAll('.tabs_let-content');
                let images = document.querySelectorAll('.tabs_image');
                let lastSectionIndex = sections.length;

                // คำนวณ index ของแท็บปัจจุบันจาก scroll position
                let currentTabIndex = Math.floor(scrollPosition / windowHeight);

                // จำกัดค่าไม่ให้เกินจำนวนแท็บที่มี
                if (currentTabIndex >= sections.length) {
                    currentTabIndex = lastSectionIndex;
                }

                // ซ่อนแท็บทั้งหมดก่อน
                sections.forEach((section, index) => {
                    section.classList.remove('is-1');
                    if (images[index]) {
                        images[index].classList.remove('is-1');
                    }
                });

                // แสดงแท็บปัจจุบัน
                if (sections[currentTabIndex]) {
                    sections[currentTabIndex].classList.add('is-1');
                    if (images[currentTabIndex]) {
                        images[currentTabIndex].classList.add('is-1');
                    }
                }
            }

            // Throttled scroll handler for tabs
            let tabsTicking = false;

            function onTabsScroll() {
                if (!tabsTicking) {
                    requestAnimationFrame(() => {
                        handleTabsScroll();
                        tabsTicking = false;
                    });
                    tabsTicking = true;
                }
            }

            window.addEventListener('scroll', onTabsScroll);
            handleTabsScroll(); // Initial call
        }

        // Countdown Timer
        function updateCountdown() {
            const eventDate = new Date('2025-08-05T09:00:00+07:00').getTime();
            const now = new Date().getTime();
            const distance = eventDate - now;

            if (distance > 0) {
                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                document.getElementById('days').textContent = days.toString().padStart(2, '0');
                document.getElementById('hours').textContent = hours.toString().padStart(2, '0');
                document.getElementById('minutes').textContent = minutes.toString().padStart(2, '0');
                document.getElementById('seconds').textContent = seconds.toString().padStart(2, '0');
            } else {
                document.getElementById('days').textContent = '00';
                document.getElementById('hours').textContent = '00';
                document.getElementById('minutes').textContent = '00';
                document.getElementById('seconds').textContent = '00';
            }
        }

        // Update countdown every second
        setInterval(updateCountdown, 1000);
        updateCountdown(); // Initial call
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
