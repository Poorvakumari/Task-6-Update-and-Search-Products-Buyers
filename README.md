# Task 6: Update and Search Products or Buyers


  ##  Requirements

  ###  XAMPP / WAMP / LAMP (Local Server)
  ###  PHP 8+
  ###  MySQL Database
  ###  Web Browser (Chrome, Firefox, etc.)

  ## Setup

  1. **Clone the Repository**:

    ```bash
    git clone https://github.com/Poorvakumari/Task-6-Update-and-Search-Products-Buyers.git
    cd Task-6-Update-and-Search-Products-Buyers
    ```

  2. **Place the project folder inside `htdocs` (for XAMPP) or `www` (for WAMP).**

  3. **Start the Apache server in XAMPP.**

  4. **Create the Database**
    1. Open phpMyAdmin (http://localhost/phpmyadmin/).
    2. Import the `schema.sql` file.

  5. Open your web browser and navigate to `http://localhost/Task-6-Update-and-Search-Products-Buyers/`.


## Required Screenshots

### 1. Home Page
- Screenshot of the main dashboard showing the navigation menu and welcome message
<img src="/images/home-page.png"/>

### 2. Products Page
- Screenshot showing the products listing with search and filter functionality
- Should include:
  - Search bar
  - Category filter dropdown
  - Price range filters
  - Clear search button
  - Product table with columns (ID, Name, Category, Price, Stock, Actions)

<img src="/images/Products-page.png">

### 3. Product Search Results
- Screenshot showing filtered product results
<img src="/images/product-search-result.png">


### 4. Product Edit Modal
- Screenshot of the edit product modal
- Should show:
  - All form fields (Name, Category, Price, Stock)
  - Save and Close buttons
  - Pre-filled data from existing product
  - Success message after updating a product
<img src="/images/product-edit-modal.png.png"/>
<img src="/images/product-success.png">

### 5. Buyers Page
- Screenshot showing the buyers listing with search and filter functionality
- Should include:
  - Search bar
  - Location filter dropdown
  - Clear search button
  - Buyer table with columns (ID, Name, Email, Phone, Location, Actions)
  - Pagination controls at the bottom

<img src="/images/buyers-page.png">

### 6. Buyer Search Results
- Screenshot showing filtered buyer results
<img src="/images/buyers-search-result.png"/>

### 7. Buyer Edit Modal
- Screenshot of the edit buyer modal
- Should show:
  - All form fields (Name, Email, Phone, Location)
  - Save and Close buttons
  - Pre-filled data from existing buyer
  - Success message after updating a buyer


<img src="/images/buyer-edit-modal.png"/>
<br/>
<img src="/images/buyer-after-location-update.png"/>
