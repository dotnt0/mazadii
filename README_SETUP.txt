========================================================
  MAZADI — مزادي | Online Auction Platform
  IT 1390 Web Systems — Phase 3 Setup Guide
  Al Imam Mohammad Ibn Saud Islamic University
========================================================

PREREQUISITES
-------------
  • XAMPP installed (Apache + MySQL modules must be RUNNING)
  • A web browser (Chrome, Firefox, Edge)


STEP 1 — COPY FILES TO XAMPP
------------------------------
  Copy the entire "mazadi" folder into:
    C:\xampp\htdocs\mazadi\

  Final structure should look like:
    C:\xampp\htdocs\mazadi\
      index.php
      style.css
      setup.php
      api\
        db.php
        auth.php
        auctions.php
        bids.php
        reviews.php
        upload.php
      uploads\


STEP 2 — START XAMPP
----------------------
  Open the XAMPP Control Panel and click START for:
    [✓] Apache
    [✓] MySQL


STEP 3 — RUN DATABASE SETUP (once only)
-----------------------------------------
  Open your browser and go to:
    http://localhost/mazadi/setup.php

  You should see green checkmarks for each step.
  This creates:
    • Database:     mazadi
    • Tables:       users, auctions, bids, reviews
    • Admin user:   admin / admin123
    • Sample user:  mazadi_seller / seller123
    • Sample data:  3 auctions + reviews

  ⚠️  DELETE setup.php after setup is complete (security risk)!


STEP 4 — OPEN THE WEBSITE
---------------------------
  Go to:
    http://localhost/mazadi/index.php

  The site will load with 3 sample auctions from the database.


TEST ACCOUNTS
-------------
  Regular user:  mazadi_seller  /  seller123
  Admin:         admin           /  admin123

  To test admin:  Log In → Admin Log In (bottom of login page)
  Admin panel:   Menu | Auction Editor | Auction Remover


FEATURES THAT NOW WORK WITH THE DATABASE
------------------------------------------
  ✓ Sign Up — creates a real account in MySQL
  ✓ Log In / Log Out — PHP sessions, server-side
  ✓ Create Auction — saved to DB, appears on home page
  ✓ Image Upload — saved to uploads/ folder
  ✓ Place Bid — validated server-side, updates DB in real time
  ✓ Reviews — saved to DB, displayed on item page
  ✓ Search — full-text search across title, category, description
  ✓ Admin Stats — live bid counts and recent bidder list
  ✓ Admin Editor — edit auction title, description, starting bid
  ✓ Admin Remover — soft-delete auctions from the platform


PUBLISHING TO A REAL DOMAIN
-----------------------------
  If your hosting provider supports PHP + MySQL (cPanel, etc.):
  1. Upload all files to public_html/ (or your domain root)
  2. Create a MySQL database from cPanel → MySQL Databases
  3. Edit api/db.php — update DB_HOST, DB_NAME, DB_USER, DB_PASS
  4. Visit https://yourdomain.com/setup.php to create the tables
  5. Delete setup.php
  6. Your site is live at https://yourdomain.com/index.php


TROUBLESHOOTING
---------------
  "Could not reach the server" on home page:
    → Make sure Apache AND MySQL are both started in XAMPP
    → Make sure you opened the site as http://localhost/mazadi/index.php
       (NOT by double-clicking index.php as a file)

  "Database connection failed" in setup.php:
    → Check DB_USER / DB_PASS in api/db.php match your MySQL setup
    → Default XAMPP: user = root, password = (empty)

  Images not uploading:
    → Make sure the uploads/ folder exists inside mazadi/
    → On Linux/Mac: chmod 755 uploads/

  Admin login not working:
    → Run setup.php again — admin user may not have been created
    → Or create the admin manually in phpMyAdmin


========================================================
  Course: IT 1390 Web Systems | 1st Semester 2026
========================================================
