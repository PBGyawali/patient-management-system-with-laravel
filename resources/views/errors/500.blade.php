<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    
    <link rel="stylesheet" type="text/css" href="{{asset('css')}}/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="{{asset('css')}}/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="{{asset('css')}}/style.css">
</head>
<body>
    <div class="main-wrapper error-wrapper">
        <div class="error-box">
            <h1>500</h1>
            <h3><i class="fa fa-warning"></i> Oops! Something went wrong</h3>
            <p>The page you requested was not found.</p>
            <a href="{{route('dashboard')}}" class="btn btn-primary go-home">Go to Home</a>
        </div>
@include('layouts.footer')