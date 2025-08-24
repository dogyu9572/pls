<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $title ?? '백오피스' }}</title>

<!-- Google Fonts - Pretendard -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Pretendard:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<!-- Bootstrap CSS (공통) -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- 커스텀 CSS -->
<link rel="stylesheet" href="{{ asset('css/backoffice.css') }}">
<link rel="stylesheet" href="{{ asset('css/common/buttons.css') }}">
<link rel="stylesheet" href="{{ asset('css/common/forms.css') }}">
<link rel="stylesheet" href="{{ asset('css/components/cards.css') }}">
<link rel="stylesheet" href="{{ asset('css/components/alerts.css') }}">
<link rel="stylesheet" href="{{ asset('css/backoffice/boards.css') }}">

<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">
