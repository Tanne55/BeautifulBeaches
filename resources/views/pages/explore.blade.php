@extends('layouts.app')

@section('title', 'Explore')

@section('content')
    <!-- banner container -->
    <section class="contact-banner mb-5">
        <div class="overlay">
            <h1 id="banner-title">Explore Tours</h1>
        </div>
        <img src="/assets/img1/aa.png" alt="" class="brush-bottom" />
    </section>




    <!-- Main1 content -->
    <section class="my-5">
        <div class="main-container">
            <!-- Search Sidebar -->
            <div class="search-sidebar">
                <h2 class="search-title">Search Tours</h2>

                <div class="search-group">
                    <label class="search-label" for="search-title">Search by Title</label>
                    <input type="text" id="search-title" class="search-input" placeholder="Enter tour title..."
                        oninput="filterTours()">
                </div>

                <div class="search-group">
                    <label class="search-label" for="search-region">Region</label>
                    <select id="search-region" class="search-select" onchange="filterTours()">
                        <option value="">All Regions</option>
                    </select>
                </div>

                <div class="search-group">
                    <label class="search-label" for="search-tag">Activity</label>
                    <select id="search-tag" class="search-select" onchange="filterTours()">
                        <option value="">All Activities</option>
                    </select>
                </div>

                <button class="clear-filters" onclick="clearFilters()">Clear Filters</button>
            </div>

            <!-- Tours Container -->
            <div class="tours-container">
                <div class="tours-header">
                    <div>
                        <span class="tours-count" id="tours-count">0 Tours found</span>
                        <a href="#" class="clear-link" onclick="clearFilters()">Clear filter</a>
                    </div>
                    <div class="sort-controls">
                        <span class="sort-label">Sort By</span>
                        <select class="search-select" style="width: auto; min-width: 120px;">
                            <option>Price: Low to High</option>
                            <option>Price: High to Low</option>
                            <option>Rating</option>
                            <option>Duration</option>
                        </select>
                        <div class="view-toggle">
                            <button class="view-btn active">≣</button>
                            <button class="view-btn">⊞</button>
                        </div>
                    </div>
                </div>

                <div class="tours-list" id="tours-list">

                    <!-- Tours will be rendered here -->
                </div>
            </div>
        </div>
    </section>
@endsection