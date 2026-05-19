<?php
/*
  index.php — Mazadi Online Auction Platform
  IT 1390 Web Systems Project — Phase 3 (PHP + MySQL)
  Course: IT1390 | Al Imam Mohammad Ibn Saud Islamic University
  Semester: 1st 2026

  File structure:
    index.php        — Main SPA entry point  ← YOU ARE HERE
    style.css        — External stylesheet
    api/db.php       — DB connection + session helpers
    api/auth.php     — Login / signup / logout
    api/auctions.php — Auction CRUD + search + admin stats
    api/bids.php     — Place bid / list bids
    api/reviews.php  — Add review / list reviews
    api/upload.php   — Image upload handler
    setup.php        — One-click DB setup (delete after use!)
    uploads/         — Uploaded auction images

  All placeholder images sourced from Unsplash (unsplash.com).
*/
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mazadi — مزادي | Online Auction Platform</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- ============================================================
     HEADER
     ============================================================ -->
<header>
    <h1 class="site-title" onclick="showPage('home')">
        MAZADI <span class="arabic">مزادي</span>
    </h1>
    <p class="site-tagline">Saudi Arabia's Online Auction Platform</p>
</header>

<!-- ============================================================
     NAVIGATION BAR
     ============================================================ -->
<nav>
    <div class="nav-inner">

        <div class="nav-links">
            <a class="nav-link" onclick="showPage('home')">Home</a>
            <a class="nav-link" onclick="showPage('about')">About Us</a>
            <a class="nav-link" onclick="showPage('create')">Create Auction</a>
        </div>

        <form class="search-form" onsubmit="handleSearch(event)">
            <label for="search-input">Search:</label>
            <input type="text" id="search-input" placeholder="Search auctions...">
            <button type="submit">Go</button>
        </form>

        <!-- Shown when NOT logged in -->
        <div class="auth-buttons" id="nav-guest">
            <a class="btn-login"  onclick="showPage('login')">Log In</a>
            <a class="btn-signup" onclick="showPage('login')">Sign Up</a>
        </div>

        <!-- Shown when logged in (hidden by default, toggled by JS) -->
        <div class="auth-buttons hidden" id="nav-user">
            <span id="nav-username-text"
                  style="color:#cce8f0;font-size:0.85rem;padding:5px 8px;display:inline-block;"></span>
            <a class="btn-login" onclick="handleLogout()" style="cursor:pointer;">Log Out</a>
        </div>

    </div>
</nav>


<!-- ============================================================
     PAGE 1 — HOME
     ============================================================ -->
<div id="page-home" class="page">

    <div class="hero">
        <h2>Bid Smart. Buy Fair.</h2>
        <p>Saudi Arabia's leading online auction platform — vehicles, electronics, watches &amp; more</p>
        <div class="hero-buttons">
            <a class="btn-primary" onclick="showPage('create')">+ Create Auction</a>
            <a class="btn-outline" onclick="showPage('login')">Log In / Sign Up</a>
        </div>
    </div>

    <div class="section">
        <h2 class="section-title">🏷️ Active Auctions</h2>
        <!-- Auction cards injected here by JavaScript after fetching from api/auctions.php -->
        <div id="auctions-container">
            <p style="color:#777;padding:20px 0;">Loading auctions…</p>
        </div>
    </div>

    <div class="about-summary">
        <h2>About Us</h2>
        <p>We are a <strong>Saudi Auction house website</strong> who strive for a
           <em>fair and easy to use bidding experience</em>. Mazadi was founded to introduce
           a trusted online auction platform to the Saudi market.</p>
        <a class="btn-primary" onclick="showPage('about')">Learn More →</a>
    </div>

</div><!-- end page-home -->


<!-- ============================================================
     PAGE 2 — ABOUT US
     ============================================================ -->
<div id="page-about" class="page hidden">
    <div class="section">
        <h2 class="section-title">About Us — من نحن</h2>

        <div class="about-layout">
            <div class="about-image-col">
                <!-- Image source: Unsplash (unsplash.com) — team / office / group meeting -->
                <img src="https://images.unsplash.com/photo-1633457896836-f8d6025c85d1?w=800&q=80"
                     alt="The Mazadi Team" class="about-img">
                <p class="img-caption">The Mazadi Team — committed to fair auctions</p>
            </div>
            <div class="about-text-col">
                <h3>Who We Are</h3>
                <p>We are a <strong>Saudi Auction house website</strong> who strive for a
                   <em>fair and easy to use bidding experience</em>. Mazadi (مزادي) was built
                   to fill a gap in the Saudi digital market: the lack of a trusted, transparent,
                   and accessible online auction platform.</p>
                <p>Our platform allows <strong>anyone</strong> to auction off vehicles, electronics,
                   watches, collectibles, and more — while giving bidders the confidence that the
                   price they pay is <u>dictated by the market</u>, not by a seller.</p>
                <p>Whether you are in <strong>Riyadh, Jeddah, Dammam</strong>, or anywhere in the
                   world, Mazadi gives you access to authentic items at fair prices.</p>
            </div>
        </div>

        <div class="cards-row">
            <div class="card">
                <p class="card-icon">🎯</p><h4>Our Mission</h4>
                <p>To provide Saudi Arabia with a safe, fair, and transparent online auction
                   platform that simplifies the buying and selling experience for everyone.</p>
            </div>
            <div class="card">
                <p class="card-icon">🌍</p><h4>Global Reach</h4>
                <p>For buyers outside Saudi Arabia, Mazadi removes the guesswork on pricing.
                   The competitive bidding process ensures you pay what the item is truly worth.</p>
            </div>
            <div class="card">
                <p class="card-icon">🛡️</p><h4>Trust &amp; Safety</h4>
                <p>Every listing is reviewed by our admin team. Problematic auctions are flagged
                   and removed promptly to maintain the integrity of our platform.</p>
            </div>
        </div>

        <div class="info-box">
            <h3>What We Offer</h3>
            <ul>
                <li>🏷️ <strong>Live Bidding</strong> — Real-time bid updates on active auctions</li>
                <li>📦 <strong>Wide Categories</strong> — Vehicles, electronics, watches, jewelry, collectibles</li>
                <li>✅ <strong>Verified Listings</strong> — Admin-reviewed items for quality assurance</li>
                <li>📍 <strong>Location Aware</strong> — Filter auctions by city across Saudi Arabia</li>
                <li>🔐 <strong>Secure Accounts</strong> — Seller and buyer accounts with full history</li>
            </ul>
        </div>

        <div class="project-info">
            <p><strong>Website Name:</strong> Mazadi — مزادي</p>
            <p><strong>Topic:</strong> Online Auction Directory for Saudi Arabia</p>
            <p><strong>Target Audience:</strong> People in Saudi Arabia and worldwide looking to buy
               or sell used items at fair market prices</p>
            <p><strong>Course:</strong> IT 1390 Web Systems — Al Imam Mohammad Ibn Saud Islamic University,
               1st Semester 2026</p>
        </div>
    </div>
</div><!-- end page-about -->


<!-- ============================================================
     PAGE 3 — ITEM PROFILE  (auction detail + bidding + reviews)
     ============================================================ -->
<div id="page-item" class="page hidden">
    <div class="section">

        <p class="breadcrumb">
            <a onclick="showPage('home')">Home</a> ›
            <span id="item-category">Category</span> ›
            <strong id="item-title-crumb">Item</strong>
        </p>

        <!-- Image source: Unsplash (unsplash.com) — replaced dynamically -->
        <img id="item-main-img"
             src="https://images.unsplash.com/photo-1672173625722-04fd626667e1?w=900&q=80"
             alt="Auction Item" class="item-main-img">

        <div class="item-layout">
            <div class="item-details">
                <h2 id="item-title">Item Title</h2>
                <p class="meta" id="item-meta">📍 Location | 🗂️ Category | 👤 Seller</p>
                <p class="meta" id="item-date">📅 Created: — | ⭐ Rating: —</p>
                <h4>Description</h4>
                <p id="item-description">Description goes here.</p>
            </div>

            <div class="bid-box-large">
                <p><em>Starting Bid</em></p>
                <p id="item-starting" class="starting-bid">—</p>
                <p><em>Current Highest Bid</em></p>
                <p id="item-current" class="bid-amount-large">—</p>
                <p class="bid-meta" id="item-bids">— bids</p>
                <hr>
                <label for="bid-input"><strong>Place Bid (SAR)</strong></label>
                <input type="number" id="bid-input" placeholder="Enter amount">
                <button onclick="placeBid()" class="btn-primary full-width">Place Bid →</button>
                <p id="bid-message" class="bid-message"></p>
            </div>
        </div>

        <div class="reviews-section">
            <h3>Reviews (<span id="review-count">0</span>)</h3>
            <div id="reviews-list"></div>
        </div>

        <div class="add-review">
            <h3>Add Your Review</h3>
            <form onsubmit="submitReview(event)">
                <div class="form-row">
                    <div class="form-group">
                        <label for="review-name">Your Name *</label>
                        <input type="text" id="review-name" placeholder="e.g. Mohammed Al-Harbi" required>
                    </div>
                    <div class="form-group">
                        <label for="review-rating">Rating *</label>
                        <select id="review-rating">
                            <option value="5">★★★★★ — 5/5</option>
                            <option value="4">★★★★☆ — 4/5</option>
                            <option value="3">★★★☆☆ — 3/5</option>
                            <option value="2">★★☆☆☆ — 2/5</option>
                            <option value="1">★☆☆☆☆ — 1/5</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="review-body">Review *</label>
                    <textarea id="review-body" rows="4"
                              placeholder="Share your experience with this auction..." required></textarea>
                </div>
                <button type="submit" class="btn-primary">Submit Review</button>
                <p id="review-message" class="bid-message"></p>
            </form>
        </div>

        <p><a class="back-link" onclick="showPage('home')">← Back to All Auctions</a></p>

    </div>
</div><!-- end page-item -->


<!-- ============================================================
     PAGE 4 — LOG IN / SIGN UP
     ============================================================ -->
<div id="page-login" class="page hidden">
    <div class="auth-container">

        <div class="tab-switcher">
            <button class="tab-btn active" id="tab-login"  onclick="switchAuthTab('login')">Log In</button>
            <button class="tab-btn"        id="tab-signup" onclick="switchAuthTab('signup')">Sign Up</button>
        </div>

        <!-- LOG IN FORM -->
        <div id="form-login">
            <h2>Log In</h2>
            <p style="text-align:center;color:#777;font-style:italic;margin-bottom:20px;">
                Welcome back to Mazadi — مزادي</p>
            <div class="dark-form">
                <form onsubmit="handleLogin(event)">
                    <label for="login-user">User Name
                        <input type="text" id="login-user" placeholder="Enter your username" required>
                    </label>
                    <label for="login-pass">Password
                        <input type="password" id="login-pass" placeholder="Enter your password" required>
                    </label>
                    <p id="login-error" class="error-msg"></p>
                    <button type="submit" class="btn-primary full-width">Log In →</button>
                </form>
                <p class="switch-link">Don't have an account?
                    <a onclick="switchAuthTab('signup')" style="cursor:pointer;">Sign Up</a></p>
            </div>
        </div>

        <!-- SIGN UP FORM -->
        <div id="form-signup" class="hidden">
            <h2>Sign Up</h2>
            <p style="text-align:center;color:#777;font-style:italic;margin-bottom:20px;">
                Create your free Mazadi account</p>
            <div class="dark-form">
                <form onsubmit="handleSignup(event)">
                    <label for="signup-user">User Name
                        <input type="text" id="signup-user" placeholder="Choose a username" required>
                    </label>
                    <label for="signup-email">Email Address
                        <input type="email" id="signup-email" placeholder="your@email.com" required>
                    </label>
                    <label for="signup-pass">Password
                        <input type="password" id="signup-pass" placeholder="Create a password (min 6 chars)" required>
                    </label>
                    <label for="signup-confirm">Confirm Password
                        <input type="password" id="signup-confirm" placeholder="Repeat your password" required>
                    </label>
                    <p id="signup-message" class="error-msg"></p>
                    <button type="submit" class="btn-signup-form">Create Account →</button>
                </form>
                <p class="switch-link">Already have an account?
                    <a onclick="switchAuthTab('login')" style="cursor:pointer;">Log In</a></p>
            </div>
        </div>

        <p class="admin-link">
            Administrator? <a onclick="showPage('admin-login')" style="cursor:pointer;">Admin Log In →</a>
        </p>

    </div>
</div><!-- end page-login -->


<!-- ============================================================
     PAGE 5 — CREATE AUCTION
     ============================================================ -->
<div id="page-create" class="page hidden">
    <div class="section" style="max-width:700px;margin:0 auto;">
        <h2 class="section-title" style="text-align:center;">Create Auction</h2>
        <p style="text-align:center;font-style:italic;color:#777;margin-bottom:24px;">
            Fill in the details below to list your item for bidding on Mazadi</p>

        <form onsubmit="submitAuction(event)">
            <div class="form-group">
                <label>Item Picture</label>
                <div class="upload-box" onclick="document.getElementById('img-file-input').click()">
                    <img id="img-preview" src="" alt="" style="display:none;width:100%;max-height:300px;object-fit:cover;">
                    <div id="upload-placeholder">
                        <p style="font-size:3rem;margin:0;">📷</p>
                        <p><strong>Click to upload item photo</strong></p>
                        <p style="color:#888;font-size:0.8rem;">JPG, PNG or GIF — max 5MB</p>
                    </div>
                    <input type="file" id="img-file-input" accept="image/*"
                           style="display:none;" onchange="previewImage(event)">
                </div>
            </div>

            <div class="form-group">
                <label for="item-name">Item Name *</label>
                <input type="text" id="item-name"
                       placeholder="e.g. 1972 Ford Mustang – Classic Muscle Car" required>
            </div>

            <div class="form-group">
                <label for="item-cat">Category</label>
                <select id="item-cat">
                    <option>Vehicles</option>
                    <option>Electronics</option>
                    <option>Watches &amp; Jewelry</option>
                    <option>Furniture</option>
                    <option>Collectibles</option>
                    <option>Real Estate</option>
                    <option>Other</option>
                </select>
            </div>

            <div class="form-group">
                <label for="item-desc">Item Description *</label>
                <textarea id="item-desc" rows="6"
                          placeholder="Describe your item: condition, history, what's included, etc." required></textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="starting-bid">Starting Bid (SAR) *</label>
                    <input type="number" id="starting-bid" placeholder="e.g. 5000" min="1" required>
                </div>
                <div class="form-group">
                    <label for="duration">Auction Duration</label>
                    <select id="duration">
                        <option>1 day</option>
                        <option>3 days</option>
                        <option>5 days</option>
                        <option selected>7 days</option>
                        <option>14 days</option>
                        <option>30 days</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="item-location">Item Location (City, Saudi Arabia) *</label>
                <input type="text" id="item-location"
                       placeholder="e.g. Riyadh, Jeddah, Dammam..." required>
            </div>

            <div class="form-buttons">
                <button type="submit" class="btn-primary">Submit Auction Listing</button>
                <a class="btn-cancel" onclick="showPage('home')" style="cursor:pointer;">Cancel</a>
            </div>
        </form>
    </div>
</div><!-- end page-create -->


<!-- ============================================================
     PAGE 6 — ADMIN LOG IN
     ============================================================ -->
<div id="page-admin-login" class="page hidden">
    <div class="auth-container">
        <div style="text-align:center;margin-bottom:16px;">
            <span class="admin-badge">🛡️ ADMIN ACCESS ONLY</span>
        </div>
        <h2 style="text-align:center;color:#8b4513;margin-bottom:6px;">Admin Log In</h2>
        <p style="text-align:center;font-style:italic;color:#777;margin-bottom:24px;">
            This page is restricted to authorized administrators only.</p>

        <div class="dark-form" style="box-shadow:0 4px 16px rgba(139,69,19,0.2);">
            <form onsubmit="handleAdminLogin(event)">
                <label for="admin-user">User Name
                    <input type="text" id="admin-user" placeholder="Admin username" required>
                </label>
                <label for="admin-pass">Password
                    <input type="password" id="admin-pass" placeholder="Admin password" required>
                </label>
                <p id="admin-error" class="error-msg"></p>
                <button type="submit" class="btn-admin">Admin Log In →</button>
                <p style="text-align:center;color:#aaa;font-size:0.78rem;margin-top:14px;font-style:italic;">
                    Default credentials: <strong style="color:#cce8f0;">admin / admin123</strong>
                </p>
            </form>
        </div>
        <p class="admin-link">
            Regular user? <a onclick="showPage('login')" style="cursor:pointer;">Log In here →</a>
        </p>
    </div>
</div><!-- end page-admin-login -->


<!-- ============================================================
     PAGE 7 — ADMIN CONTROL PANEL
     ============================================================ -->
<div id="page-admin-panel" class="page hidden">

    <div class="admin-page-header">
        <h2>Control Panel</h2>
        <p style="font-style:italic;color:#f0d0b0;font-size:0.88rem;margin-top:4px;">
            Mazadi Admin Dashboard</p>
    </div>

    <div class="section">

        <div class="admin-tabs">
            <button class="admin-tab active" id="atab-menu"    onclick="switchAdminTab('menu')">Menu</button>
            <button class="admin-tab"        id="atab-editor"  onclick="switchAdminTab('editor')">Auction Editor</button>
            <button class="admin-tab"        id="atab-remover" onclick="switchAdminTab('remover')">Auction Remover</button>
        </div>

        <div class="admin-tab-content">

            <!-- ── MENU TAB ── -->
            <div id="admin-menu" class="admin-panel">
                <h3>Website Management</h3>
                <p style="color:#555;font-size:0.88rem;margin-bottom:20px;">
                    Overview of help requests, auction details, and user bidding activity.</p>

                <div class="help-requests">
                    <h4>📩 Help Requests (2)</h4>
                    <div class="help-item">
                        <p><strong>Omar K.</strong> — <em>2026-04-22</em></p>
                        <p>I cannot place a bid on listing #2 — the button is not responding.</p>
                    </div>
                    <div class="help-item">
                        <p><strong>Noura F.</strong> — <em>2026-04-21</em></p>
                        <p>My review was not saved for listing #3. Can you check?</p>
                    </div>
                </div>

                <h4 style="border-bottom:1px solid #1a6b8a;padding-bottom:6px;margin-top:24px;margin-bottom:14px;">
                    User activity map showing most popular auctions:</h4>

                <!-- Dynamic content loaded by loadAdminMenu() -->
                <div class="activity-map">
                    <div class="activity-images" id="admin-activity-images">
                        <!-- Image source: Unsplash (unsplash.com) -->
                        <img src="https://images.unsplash.com/photo-1672173625722-04fd626667e1?w=400&q=70" alt="Auction 1">
                        <img src="https://images.unsplash.com/photo-1763189851330-23f36450bbde?w=400&q=70" alt="Auction 2">
                        <img src="https://images.unsplash.com/photo-1607603289612-71ae134aa577?w=400&q=70" alt="Auction 3">
                    </div>
                    <div class="activity-stats" id="admin-stats-content">
                        <p style="color:#cce8f0;font-style:italic;">Loading live stats…</p>
                    </div>
                </div>
            </div><!-- end admin-menu -->

            <!-- ── AUCTION EDITOR TAB ── -->
            <div id="admin-editor" class="admin-panel hidden">
                <h3>Auction Editor</h3>
                <p style="color:#555;font-size:0.88rem;margin-bottom:20px;">
                    Select an active auction, then edit its details below.</p>

                <div class="info-box">
                    <label for="edit-select"><strong>Step 1: Select an auction to edit</strong></label>
                    <select id="edit-select" onchange="loadEditForm()" style="margin-top:8px;">
                        <option value="">— Select an auction —</option>
                    </select>
                </div>

                <div id="edit-form-wrapper" class="hidden"
                     style="margin-top:20px;border:2px solid #1a6b8a;border-radius:6px;padding:18px;">
                    <h4 style="color:#1a6b8a;margin-bottom:14px;">Step 2: Edit Auction Details</h4>
                    <div class="form-group">
                        <label for="edit-name">Item Name</label>
                        <input type="text" id="edit-name">
                    </div>
                    <div class="form-group">
                        <label for="edit-desc">Description</label>
                        <textarea id="edit-desc" rows="4"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="edit-bid">Starting Bid (SAR)</label>
                        <input type="number" id="edit-bid">
                    </div>
                    <button onclick="saveEdit()" class="btn-primary">Save Changes</button>
                    <p id="edit-message" class="bid-message"></p>
                </div>
            </div><!-- end admin-editor -->

            <!-- ── AUCTION REMOVER TAB ── -->
            <div id="admin-remover" class="admin-panel hidden">
                <h3 style="color:#c0392b;">Auction Remover</h3>
                <p style="color:#555;font-size:0.88rem;margin-bottom:20px;">
                    Select a problematic or completed auction to remove it from the platform.</p>

                <div style="background:#fff8f8;border:2px solid #c0392b;border-radius:6px;padding:18px;">
                    <form onsubmit="deleteAuction(event)">
                        <p><strong>Select an auction to delete:</strong></p><br>
                        <!-- Dynamic radio cards injected by loadAdminAuctionSelects() -->
                        <div id="admin-remover-cards">
                            <p style="color:#777;font-style:italic;">Loading auctions…</p>
                        </div>
                        <button type="submit" class="btn-delete">🗑️ Delete Selected Auction</button>
                        <p id="delete-message" class="bid-message"></p>
                    </form>
                </div>
            </div><!-- end admin-remover -->

        </div><!-- end .admin-tab-content -->

        <p style="text-align:center;margin-top:16px;">
            <a onclick="showPage('home')" style="color:#8b4513;font-size:0.88rem;cursor:pointer;">
                ← Back to Main Website</a>
        </p>

    </div>
</div><!-- end page-admin-panel -->


<!-- ============================================================
     FOOTER
     ============================================================ -->
<footer>
    <p class="footer-title">MAZADI — مزادي</p>
    <p><em>Saudi Arabia's trusted online auction platform</em></p>
    <div class="footer-links">
        <a onclick="showPage('home')"        style="cursor:pointer;">Home</a>
        <a onclick="showPage('about')"       style="cursor:pointer;">About Us</a>
        <a onclick="showPage('create')"      style="cursor:pointer;">Create Auction</a>
        <a onclick="showPage('login')"       style="cursor:pointer;">Log In</a>
        <a onclick="showPage('admin-login')" style="cursor:pointer;">Admin</a>
    </div>
    <p class="footer-copy">
        © 2026 Mazadi. IT 1390 Web Systems Project – Al Imam Mohammad Ibn Saud Islamic University.
    </p>
</footer>


<!-- ============================================================
     JAVASCRIPT — Phase 3 — real fetch() calls to PHP/MySQL API
     ============================================================ -->
<script>

/* ── Config ── */
var API              = 'api/';
var currentUser      = null;
var currentAuctionId = null;

/* ============================================================
   INIT
   ============================================================ */
document.addEventListener('DOMContentLoaded', function () {
    checkSession().then(function () { loadAuctions(); });
});

/* ============================================================
   SESSION
   ============================================================ */
async function checkSession() {
    try {
        var r = await fetch(API + 'auth.php?action=me');
        currentUser = (await r.json()).user;
    } catch (e) { currentUser = null; }
    updateNavForUser();
}

function updateNavForUser() {
    var g = document.getElementById('nav-guest');
    var u = document.getElementById('nav-user');
    var t = document.getElementById('nav-username-text');
    if (currentUser) {
        if (g) g.classList.add('hidden');
        if (u) u.classList.remove('hidden');
        if (t) t.textContent = '👤 ' + currentUser.username;
    } else {
        if (g) g.classList.remove('hidden');
        if (u) u.classList.add('hidden');
    }
}

/* ============================================================
   NAVIGATION
   ============================================================ */
function showPage(pageId) {
    if (pageId === 'create' && !currentUser) { showPage('login'); return; }
    document.querySelectorAll('.page').forEach(function (p) { p.classList.add('hidden'); });
    var t = document.getElementById('page-' + pageId);
    if (t) t.classList.remove('hidden');
    window.scrollTo(0, 0);
    if (pageId === 'admin-panel') loadAdminMenu();
    if (pageId === 'home')        loadAuctions();
}

/* ============================================================
   HOME — load auctions from DB
   ============================================================ */
async function loadAuctions(search) {
    var url = API + 'auctions.php';
    if (search) url += '?search=' + encodeURIComponent(search);
    var c = document.getElementById('auctions-container');
    if (c) c.innerHTML = '<p style="color:#777;padding:20px 0;">Loading auctions…</p>';
    try {
        var auctions = await (await fetch(url)).json();
        renderAuctions(Array.isArray(auctions) ? auctions : []);
    } catch (e) {
        if (c) c.innerHTML =
            '<p style="color:#c0392b;padding:20px 0;">⚠️ Could not reach the server. ' +
            'Make sure XAMPP (Apache + MySQL) is running, and open this page as ' +
            '<strong>http://74.220.51.0/24/mazadi/index.php</strong></p>';
    }
}

function renderAuctions(auctions) {
    var c = document.getElementById('auctions-container');
    if (!c) return;
    if (!auctions.length) {
        c.innerHTML = '<p style="color:#777;padding:20px 0;font-style:italic;">No active auctions found.</p>';
        return;
    }
    c.innerHTML = auctions.map(function (a, i) {
        var img  = a.image_url || 'https://images.unsplash.com/photo-1607603289612-71ae134aa577?w=900&q=80';
        var desc = esc(a.description);
        if (desc.length > 220) desc = desc.substring(0, 220) + '…';
        return (
            '<p class="listing-label"><strong>Listing ' + (i+1) + '</strong> — <em>' + esc(a.category) + '</em></p>' +
            '<div class="auction-card">' +
              '<img src="' + esc(img) + '" alt="' + esc(a.title) + '" class="auction-img" onclick="showItemPage(' + a.id + ')">' +
              '<div class="auction-info">' +
                '<div class="auction-details">' +
                  '<h3><a onclick="showItemPage(' + a.id + ')" style="cursor:pointer;">' + esc(a.title) + '</a></h3>' +
                  '<p class="meta">📍 ' + esc(a.location) + ' &nbsp;|&nbsp; 📅 Created: ' + a.created_at.split(' ')[0] + '</p>' +
                  '<p>' + desc + '</p>' +
                '</div>' +
                '<div class="bid-box">' +
                  '<p><em>Current Highest Bid</em></p>' +
                  '<p class="bid-amount">' + fmtSAR(a.current_bid) + '</p>' +
                  '<p class="bid-meta">' + a.bid_count + ' bids &nbsp;|&nbsp; Ends in: ' + fmtEnds(a.ends_at) + '</p>' +
                  '<a class="btn-bid" onclick="showItemPage(' + a.id + ')" style="cursor:pointer;">Place Bid →</a>' +
                '</div>' +
              '</div>' +
            '</div>'
        );
    }).join('');
}

/* ============================================================
   ITEM PAGE
   ============================================================ */
async function showItemPage(id) {
    currentAuctionId = id;
    showPage('item');
    document.getElementById('item-title').textContent       = 'Loading…';
    document.getElementById('item-description').textContent = '';
    document.getElementById('reviews-list').innerHTML       = '<p style="color:#777;">Loading…</p>';
    try {
        var a = await (await fetch(API + 'auctions.php?id=' + id)).json();
        if (a.error) { document.getElementById('item-title').textContent = 'Auction not found.'; return; }

        document.getElementById('item-title').textContent       = a.title;
        document.getElementById('item-title-crumb').textContent = a.title;
        document.getElementById('item-category').textContent    = a.category;
        document.getElementById('item-meta').textContent =
            '📍 ' + a.location + '  |  🗂️ ' + a.category + '  |  👤 Seller: ' + a.seller_name;
        document.getElementById('item-date').textContent =
            '📅 Created: ' + a.created_at.split(' ')[0] + '  |  ⭐ Rating: ' + avgRating(a.reviews) + ' / 5';
        document.getElementById('item-description').textContent = a.description;
        document.getElementById('item-starting').textContent    = fmtSAR(a.starting_bid);
        document.getElementById('item-current').textContent     = fmtSAR(a.current_bid);
        document.getElementById('item-bids').innerHTML =
            a.bid_count + ' bids  |  Ends in: <strong class="urgent">' + fmtEnds(a.ends_at) + '</strong>';
        document.getElementById('item-main-img').src = a.image_url ||
            'https://images.unsplash.com/photo-1607603289612-71ae134aa577?w=900&q=80';
        document.getElementById('item-main-img').alt = a.title;

        var bi = document.getElementById('bid-input');
        bi.placeholder = 'Min: ' + Math.round(parseFloat(a.current_bid) + 100).toLocaleString();
        bi.min   = parseFloat(a.current_bid) + 1;
        bi.value = '';
        bi.setAttribute('data-id',      a.id);
        bi.setAttribute('data-current', a.current_bid);
        document.getElementById('bid-message').textContent = '';
        document.getElementById('bid-message').className   = 'bid-message';
        loadReviews(a.reviews || []);
    } catch (e) {
        document.getElementById('item-title').textContent = 'Failed to load auction.';
    }
}

function loadReviews(reviews) {
    var list = document.getElementById('reviews-list');
    if (!reviews || !reviews.length) {
        list.innerHTML = '<p style="color:#777;font-style:italic;">No reviews yet. Be the first!</p>';
    } else {
        list.innerHTML = '';
        reviews.forEach(function (r) {
            var stars = '★'.repeat(r.rating) + '☆'.repeat(5 - r.rating);
            var card  = document.createElement('div');
            card.className = 'review-card';
            card.innerHTML =
                '<div class="review-header"><strong>' + esc(r.reviewer_name) + '</strong>' +
                '<span class="stars">' + stars + ' (' + r.rating + '/5)</span></div>' +
                '<p>' + esc(r.body) + '</p>' +
                '<p class="review-date">Posted on: ' + r.created_at.split(' ')[0] + '</p>';
            list.appendChild(card);
        });
    }
    document.getElementById('review-count').textContent = reviews ? reviews.length : 0;
}

/* ============================================================
   PLACE BID
   ============================================================ */
async function placeBid() {
    var input  = document.getElementById('bid-input');
    var msg    = document.getElementById('bid-message');
    var amount = parseFloat(input.value);
    var id     = parseInt(input.getAttribute('data-id'));
    var cur    = parseFloat(input.getAttribute('data-current'));

    if (!currentUser) {
        msg.textContent = '❌ You must be logged in to place a bid.';
        msg.className   = 'bid-message error'; return;
    }
    if (!amount || amount <= cur) {
        msg.textContent = '❌ Your bid must be higher than ' + fmtSAR(cur) + '.';
        msg.className   = 'bid-message error'; return;
    }
    try {
        var d = await (await fetch(API + 'bids.php', {
            method: 'POST', headers: {'Content-Type':'application/json'},
            body: JSON.stringify({auction_id: id, amount: amount})
        })).json();
        if (d.error) { msg.textContent = '❌ ' + d.error; msg.className = 'bid-message error'; return; }

        document.getElementById('item-current').textContent = fmtSAR(amount);
        input.setAttribute('data-current', amount);
        input.placeholder = 'Min: ' + Math.round(amount + 100).toLocaleString();
        input.min = amount + 1; input.value = '';
        msg.textContent = '✅ Your bid of ' + fmtSAR(amount) + ' has been placed!';
        msg.className   = 'bid-message success';
        setTimeout(function () { showItemPage(id); }, 1500);
    } catch (e) { msg.textContent = '❌ Failed to place bid.'; msg.className = 'bid-message error'; }
}

/* ============================================================
   SUBMIT REVIEW
   ============================================================ */
async function submitReview(event) {
    event.preventDefault();
    var name   = document.getElementById('review-name').value.trim();
    var rating = parseInt(document.getElementById('review-rating').value);
    var body   = document.getElementById('review-body').value.trim();
    var msg    = document.getElementById('review-message');
    if (!name || !body) { msg.textContent = 'Please fill in all required fields.'; msg.className = 'bid-message error'; return; }
    try {
        var d = await (await fetch(API + 'reviews.php', {
            method: 'POST', headers: {'Content-Type':'application/json'},
            body: JSON.stringify({auction_id: currentAuctionId, name: name, rating: rating, body: body})
        })).json();
        if (d.error) { msg.textContent = '❌ ' + d.error; msg.className = 'bid-message error'; return; }

        var stars = '★'.repeat(rating) + '☆'.repeat(5 - rating);
        var card  = document.createElement('div');
        card.className = 'review-card';
        card.innerHTML =
            '<div class="review-header"><strong>' + esc(name) + '</strong>' +
            '<span class="stars">' + stars + ' (' + rating + '/5)</span></div>' +
            '<p>' + esc(body) + '</p>' +
            '<p class="review-date">Posted on: ' + new Date().toISOString().split('T')[0] + '</p>';
        var list = document.getElementById('reviews-list');
        if (list.querySelector('p')) list.innerHTML = '';
        list.insertBefore(card, list.firstChild);
        document.getElementById('review-count').textContent =
            parseInt(document.getElementById('review-count').textContent) + 1;
        document.getElementById('review-name').value = '';
        document.getElementById('review-body').value = '';
        msg.textContent = '✅ Your review has been submitted!'; msg.className = 'bid-message success';
    } catch (e) { msg.textContent = '❌ Failed to submit review.'; msg.className = 'bid-message error'; }
}

/* ============================================================
   AUTH
   ============================================================ */
function switchAuthTab(tab) {
    document.getElementById('form-' + (tab === 'login' ? 'signup' : 'login')).classList.add('hidden');
    document.getElementById('form-' + tab).classList.remove('hidden');
    document.getElementById('tab-login').classList.toggle('active',  tab === 'login');
    document.getElementById('tab-signup').classList.toggle('active', tab === 'signup');
}

async function handleLogin(event) {
    event.preventDefault();
    var err = document.getElementById('login-error'); err.classList.remove('show');
    try {
        var d = await (await fetch(API + 'auth.php?action=login', {
            method: 'POST', headers: {'Content-Type':'application/json'},
            body: JSON.stringify({
                username: document.getElementById('login-user').value.trim(),
                password: document.getElementById('login-pass').value
            })
        })).json();
        if (d.error) { err.textContent = '❌ ' + d.error; err.classList.add('show'); return; }
        currentUser = d.user; updateNavForUser();
        document.getElementById('login-user').value = '';
        document.getElementById('login-pass').value = '';
        showPage('home');
    } catch (e) { err.textContent = '❌ Login failed. Is XAMPP running?'; err.classList.add('show'); }
}

async function handleSignup(event) {
    event.preventDefault();
    var pass = document.getElementById('signup-pass').value;
    var msg  = document.getElementById('signup-message'); msg.classList.remove('show');
    if (pass !== document.getElementById('signup-confirm').value) {
        msg.textContent = '❌ Passwords do not match.'; msg.classList.add('show'); return;
    }
    try {
        var d = await (await fetch(API + 'auth.php?action=signup', {
            method: 'POST', headers: {'Content-Type':'application/json'},
            body: JSON.stringify({
                username: document.getElementById('signup-user').value.trim(),
                email:    document.getElementById('signup-email').value.trim(),
                password: pass
            })
        })).json();
        if (d.error) { msg.textContent = '❌ ' + d.error; msg.classList.add('show'); return; }
        currentUser = d.user; updateNavForUser();
        msg.style.backgroundColor = '#d4edda'; msg.style.color = '#155724';
        msg.textContent = '✅ Account created! Redirecting…'; msg.classList.add('show');
        setTimeout(function () { showPage('home'); }, 1200);
    } catch (e) { msg.textContent = '❌ Sign-up failed. Is XAMPP running?'; msg.classList.add('show'); }
}

async function handleAdminLogin(event) {
    event.preventDefault();
    var err = document.getElementById('admin-error'); err.classList.remove('show');
    try {
        var d = await (await fetch(API + 'auth.php?action=admin_login', {
            method: 'POST', headers: {'Content-Type':'application/json'},
            body: JSON.stringify({
                username: document.getElementById('admin-user').value,
                password: document.getElementById('admin-pass').value
            })
        })).json();
        if (d.error) { err.textContent = '❌ ' + d.error; err.classList.add('show'); return; }
        await checkSession();
        showPage('admin-panel');
    } catch (e) { err.textContent = '❌ Login failed. Is XAMPP running?'; err.classList.add('show'); }
}

async function handleLogout() {
    try { await fetch(API + 'auth.php?action=logout', {method:'POST'}); } catch(e){}
    currentUser = null; updateNavForUser(); showPage('home');
}

/* ============================================================
   CREATE AUCTION
   ============================================================ */
async function submitAuction(event) {
    event.preventDefault();
    if (!currentUser) { alert('You must be logged in to create an auction.'); showPage('login'); return; }
    var durMatch = document.getElementById('duration').value.match(/(\d+)/);
    var imageUrl = '';
    var fi       = document.getElementById('img-file-input');
    if (fi.files.length > 0) {
        var fd = new FormData(); fd.append('image', fi.files[0]);
        try { var ud = await (await fetch(API + 'upload.php', {method:'POST', body:fd})).json();
              if (ud.url) imageUrl = ud.url; } catch(e){}
    }
    try {
        var d = await (await fetch(API + 'auctions.php', {
            method: 'POST', headers: {'Content-Type':'application/json'},
            body: JSON.stringify({
                title:        document.getElementById('item-name').value.trim(),
                category:     document.getElementById('item-cat').value,
                description:  document.getElementById('item-desc').value.trim(),
                starting_bid: parseFloat(document.getElementById('starting-bid').value),
                location:     document.getElementById('item-location').value.trim(),
                duration_days: durMatch ? parseInt(durMatch[1]) : 7,
                image_url:    imageUrl
            })
        })).json();
        if (d.error) { alert('❌ ' + d.error); return; }
        alert('✅ Your auction has been listed successfully!');
        event.target.reset();
        document.getElementById('img-preview').style.display        = 'none';
        document.getElementById('upload-placeholder').style.display = 'block';
        showPage('home');
    } catch(e) { alert('❌ Failed to create auction. Is XAMPP running?'); }
}

function previewImage(event) {
    var file = event.target.files[0]; if (!file) return;
    var r = new FileReader();
    r.onload = function (e) {
        var p = document.getElementById('img-preview');
        p.src = e.target.result; p.style.display = 'block';
        document.getElementById('upload-placeholder').style.display = 'none';
    };
    r.readAsDataURL(file);
}

/* ============================================================
   SEARCH
   ============================================================ */
async function handleSearch(event) {
    event.preventDefault();
    var q = document.getElementById('search-input').value.trim();
    if (!q) return;
    showPage('home');
    await loadAuctions(q);
}

/* ============================================================
   ADMIN
   ============================================================ */
function switchAdminTab(tab) {
    ['menu','editor','remover'].forEach(function (p) {
        document.getElementById('admin-' + p).classList.add('hidden');
        document.getElementById('atab-' + p).classList.remove('active');
    });
    document.getElementById('admin-' + tab).classList.remove('hidden');
    document.getElementById('atab-' + tab).classList.add('active');
    if (tab === 'editor' || tab === 'remover') loadAdminAuctionSelects();
}

async function loadAdminMenu() {
    try {
        var data = await (await fetch(API + 'auctions.php?action=stats')).json();
        if (data.error) return;
        var top  = data.top_auctions || [];
        var recs = data.recent_bids  || [];
        var maxB = top.reduce(function (m, a) { return Math.max(m, parseInt(a.bid_count)||0); }, 1);

        var imgsEl = document.getElementById('admin-activity-images');
        if (imgsEl) imgsEl.innerHTML = top.slice(0,3).map(function (a) {
            var s = a.image_url || 'https://images.unsplash.com/photo-1607603289612-71ae134aa577?w=400&q=70';
            return '<img src="' + esc(s) + '" alt="' + esc(a.title) + '">';
        }).join('');

        var statsEl = document.getElementById('admin-stats-content');
        if (!statsEl) return;
        var html = '<h4>📊 Bid Activity Overview</h4>';
        top.forEach(function (a, i) {
            var pct = Math.round((parseInt(a.bid_count) / maxB) * 100);
            html +=
                '<div class="stat-item">' +
                '<p><strong>#' + (i+1) + ' — ' + esc(a.title).substring(0,45) + '</strong></p>' +
                '<p>📍 ' + esc(a.location) + ' &nbsp;|&nbsp; 📅 ' + a.created_at.split(' ')[0] + '</p>' +
                '<p>💰 ' + fmtSAR(a.current_bid) + ' &nbsp;|&nbsp; 🔨 ' + a.bid_count + ' bids</p>' +
                '<div class="bar-track"><div class="bar-fill' + (i===0?' hot':'') +
                '" style="width:' + pct + '%;"></div></div></div>';
        });
        if (recs.length) {
            html += '<h5>Recent Bidders:</h5>';
            recs.forEach(function (b) {
                html += '<p>👤 <strong>' + esc(b.username) + '</strong> bid <strong>' +
                    fmtSAR(b.amount) + '</strong></p>';
            });
        }
        statsEl.innerHTML = html;
    } catch(e) { /* keep static fallback */ }
}

async function loadAdminAuctionSelects() {
    try {
        var auctions = await (await fetch(API + 'auctions.php')).json();
        if (!Array.isArray(auctions)) return;
        var editSel = document.getElementById('edit-select');
        editSel.innerHTML = '<option value="">— Select an auction —</option>';
        var remCon = document.getElementById('admin-remover-cards');
        if (remCon) remCon.innerHTML = '';
        auctions.forEach(function (a) {
            var opt = document.createElement('option');
            opt.value = a.id; opt.textContent = '#' + a.id + ' — ' + a.title;
            editSel.appendChild(opt);
            if (remCon) {
                var img = a.image_url || 'https://images.unsplash.com/photo-1607603289612-71ae134aa577?w=200&q=70';
                var lbl = document.createElement('label');
                lbl.className = 'radio-card'; lbl.id = 'radio-' + a.id;
                lbl.innerHTML =
                    '<input type="radio" name="del-auction" value="' + a.id +
                    '" onchange="selectDelete(' + a.id + ')">' +
                    '<img src="' + esc(img) + '" alt="' + esc(a.title) + '">' +
                    '<div><p><strong>' + esc(a.title) + '</strong></p>' +
                    '<p>📍 ' + esc(a.location) + ' &nbsp;|&nbsp; 🔨 ' + a.bid_count + ' bids</p></div>';
                remCon.appendChild(lbl);
            }
        });
    } catch(e){}
}

async function loadEditForm() {
    var id = document.getElementById('edit-select').value;
    var w  = document.getElementById('edit-form-wrapper');
    if (!id) { w.classList.add('hidden'); return; }
    try {
        var a = await (await fetch(API + 'auctions.php?id=' + id)).json();
        if (a.error) return;
        document.getElementById('edit-name').value = a.title;
        document.getElementById('edit-desc').value = a.description;
        document.getElementById('edit-bid').value  = a.starting_bid;
        w.setAttribute('data-id', a.id);
        document.getElementById('edit-message').textContent = '';
        document.getElementById('edit-message').className   = 'bid-message';
        w.classList.remove('hidden');
    } catch(e){}
}

async function saveEdit() {
    var id  = document.getElementById('edit-form-wrapper').getAttribute('data-id');
    var msg = document.getElementById('edit-message');
    try {
        var d = await (await fetch(API + 'auctions.php?action=update', {
            method: 'POST', headers: {'Content-Type':'application/json'},
            body: JSON.stringify({
                id:           parseInt(id),
                title:        document.getElementById('edit-name').value,
                description:  document.getElementById('edit-desc').value,
                starting_bid: parseFloat(document.getElementById('edit-bid').value)
            })
        })).json();
        if (d.error) { msg.textContent = '❌ ' + d.error; msg.className = 'bid-message error'; }
        else          { msg.textContent = '✅ Auction updated successfully!'; msg.className = 'bid-message success'; }
    } catch(e) { msg.textContent = '❌ Failed to save.'; msg.className = 'bid-message error'; }
}

function selectDelete(id) {
    document.querySelectorAll('.radio-card').forEach(function (c) { c.classList.remove('selected'); });
    var s = document.getElementById('radio-' + id); if (s) s.classList.add('selected');
}

async function deleteAuction(event) {
    event.preventDefault();
    var sel = document.querySelector('input[name="del-auction"]:checked');
    var msg = document.getElementById('delete-message');
    if (!sel) { msg.textContent = '❌ Please select an auction to delete.'; msg.className = 'bid-message error'; return; }
    if (!confirm('Are you sure you want to remove this auction? This cannot be undone.')) return;
    try {
        var d = await (await fetch(API + 'auctions.php?action=delete', {
            method: 'POST', headers: {'Content-Type':'application/json'},
            body: JSON.stringify({id: parseInt(sel.value)})
        })).json();
        if (d.error) { msg.textContent = '❌ ' + d.error; msg.className = 'bid-message error'; }
        else {
            msg.textContent = '✅ Auction #' + sel.value + ' has been removed.'; msg.className = 'bid-message success';
            sel.checked = false; loadAdminAuctionSelects(); loadAdminMenu();
        }
    } catch(e) { msg.textContent = '❌ Failed to delete.'; msg.className = 'bid-message error'; }
}

/* ============================================================
   UTILITIES
   ============================================================ */
function esc(str) {
    if (str == null) return '';
    var d = document.createElement('div');
    d.appendChild(document.createTextNode(String(str)));
    return d.innerHTML;
}
function fmtSAR(n) {
    return parseFloat(n).toLocaleString('en-US', {maximumFractionDigits:0}) + ' SAR';
}
function fmtEnds(endsAt) {
    var diff = new Date(endsAt) - new Date();
    if (diff <= 0) return '<strong style="color:#c0392b;">Ended</strong>';
    var d = Math.floor(diff/86400000), h = Math.floor((diff%86400000)/3600000), m = Math.floor((diff%3600000)/60000);
    var label = d > 0 ? d+'d '+h+'h '+m+'m' : h > 0 ? h+'h '+m+'m' : m+'m';
    return '<strong class="urgent">' + label + '</strong>';
}
function avgRating(reviews) {
    if (!reviews || !reviews.length) return 'No ratings';
    return (reviews.reduce(function (s,r){return s+parseInt(r.rating);},0)/reviews.length).toFixed(1);
}

</script>
</body>
</html>
