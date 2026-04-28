<?php 
  session_start();
//   session_destroy();
  include 'includes/header.php'; 
  include 'includes/navbar.php'; 
?>
    <main class="hero">
        <div class="hero-overlay">
            <div class="hero-content">
                <h2>Baked to Perfection, Crafted for You</h2>
                <p>Indulge in the art of artisanal baking, where every treat is handcrafted daily with premium ingredients and a touch of magic.</p>
                <a href="menu.php">View Menu</a>
                <a href="">Best Sellers</a>
            </div>
        </div>
    </main>


<section class="best">

        <div class="best-title">
            <h2>best sallers</h2>
            <div class="best-line"></div>
        </div>

        <div class="best-container">
            <div class="wrapper" id="loop">
                <div class="slide"><img src="assets/download (20).jpg"></div>
                <div class="slide"><img src="assets/Brown Butter Cinnamon Rolls.jpg" alt="Best Seller 2"></div>
                <div class="slide"><img src="assets/Pistachio Kunafa Cake  Crispy, Creamy, and Sweet.jpg" alt="Best Seller 3"></div>
                <div class="slide"><img src="assets/So delicious food .jpg" alt="Best Seller 4"></div>
                <div class="slide"><img src="assets/download (21).jpg" alt="Best Seller 5"></div>
                <div class="slide"><img src="assets/download (24).jpg" alt="Best Seller 6"></div>
                <div class="slide"><img src="assets/download (22).jpg" alt="Best Seller 7"></div>
                <div class="slide"><img src="assets/Healthy Protein Donuts You’ll Actually Want to Eat.jpg" alt="Best Seller 8"></div>
            </div>

        </div>

</section>

<section class="background">
    <div class="container mt-4">
        <div class="section-title text-center mb-4">
            <h2>Shop by Category</h2>
            <p>Explore our delicious categories</p>
        </div>

        <div class="row g-4 justify-content-center">
            <div class="col-md-3">
                <div class="card">
                    <img src="images/drinks.jpg">
                    <div class="overlay">
                        <h3>Drinks</h3>
                        <div class="cart-controls">
                            <a href="menu.php?cat=drinks" class="btn">View menu</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card">
                    <img src="images/desserts.jpg">
                    <div class="overlay">
                        <h3>Desserts</h3>
                        <div class="cart-controls">
                            <a href="menu.php?cat=western" class="btn">View menu</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card">
                    <img src="images/cakes.png">
                    <div class="overlay">
                        <h3>Cakes</h3>
                        <div class="cart-controls">
                            <a href="menu.php?cat=cakes" class="btn">View menu</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card">
                    <img src="images/boxes.jpg">
                    <div class="overlay">
                        <h3>Boxes</h3>
                        <div class="cart-controls">
                            <a href="menu.php?cat=boxes" class="btn">View menu</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<section class="choose">
        <div class="container"> 
            <div class="row align-items-center">
             <div class="col-lg-6 choose-content">
                <h2>Why Choose Our dessert shop?</h2>
                <div class="choose-line"></div>
                <p><i class="fa-solid fa-check"></i>Handcrafted with love. Baked fresh daily for your joy.</p>
                <p><i class="fa-solid fa-check"></i>Natural flavors. Premium butter. No shortcuts, just taste.</p>
                <p><i class="fa-solid fa-check"></i>From oven to heart.</p>
                <p><i class="fa-solid fa-check"></i>Honest baking from our kitchen to your heart.</p>
                <p><i class="fa-solid fa-check"></i>Driven by taste. Inspired by tradition. Perfectly sweet.</p>
            </div>

            <div class="col-lg-6 choose-video mt-4 mt-lg-0">
                <div class="ratio ratio-16x9">
                    <video controls loop autoplay muted>
                        <source src="assets/3992584-uhd_4096_2160_25fps.mp4">
                    </video>
                </div>
            </div>

        </div>
    </div>
</section>


    <section>
            <div class="icons-section">
                <div class="icons-overlay">
                    <div class="icons">
                        <div class="icon">
                            <div class="icon-content">
                                <img src="images/happy-costumers.png">
                                <h3>10K+</h3>
                                <p>Happy Customers</p>
                            </div>    
                        </div>
                        <div class="icon">
                            <div class="icon-content">
                                <img src="images/signature-desserts.png">
                                <h3>20+</h3>
                                <p>Signature Desserts</p>
                            </div>    
                        </div>
                    <div class="icon">
                            <div class="icon-content">
                                <img src="images/orders-served.png">
                                <h3>40K+</h3>
                                <p>Orders Served</p>
                            </div>
                        </div>
                        <div class="icon">
                            <div class="icon-content">
                                <img src="images/unique-recipes.png">
                                <h3>50+</h3>
                                <p>Unique Recipes</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </section>

<section class="background">
        <div class="section-title text-center mb-4">
            <h2>Our Team</h2>
            <p>Explore our Team members</p>
        </div>
    <div class="team-container">

            <div class="team-card">
                <div class="img-box">
                    <img src="images/team-member1.jpg" alt="team-member1">
                </div>
                <div class="content">
                    <h3>Laila Hassan</h3>
                    <span>Cake Decoration Specialist</span>
                    <p>Responsible for finishing and decorating cakes with precise details and a consistent signature style.</p>
                </div>
            </div>

            <div class="team-card">
                <div class="img-box">
                    <img src="images/team-member2.jpg" alt="team-member2">
                </div>
                <div class="content">
                    <h3>Nora Adel</h3>
                    <span>Custom Orders Coordinator</span>
                    <p>Handles customer requests and ensures each order is prepared exactly as requested.</p>
                </div>
            </div>

            <div class="team-card">
                <div class="img-box">
                    <img src="images/team-member3.jpg" alt="team-member3">
                </div>
                <div class="content">
                    <h3>Salma Ali</h3>
                    <span>Chocolate & Dessert Production</span>
                    <p>Focuses on preparing chocolate-based desserts with consistent quality and flavor.</p>
                </div>
            </div>

            <div class="team-card">
                <div class="img-box">
                    <img src="images/team-member4.jpg" alt="team-member4">
                </div>
                <div class="content">
                    <h3>Talia Nabil</h3>
                    <span>Marketing & Social Media</span>
                    <p>Manages the brand’s online presence, creates engaging content, and promotes new products and seasonal offers.</p>
                </div>
            </div>
        </div>
</section>

<section class="about-section">

        <div class="container about-wrapper">

            <div class="about-image">
                <img src="images/bakery1.png" alt="Bakery Image">
            </div>

            <div class="about-text">
                <h2>Our Story</h2>

                <p>
                    We started with a simple passion: baking desserts that make people smile.  
                    Every cake, cupcake, and sweet treat is handcrafted with love, fresh ingredients, and attention to detail.
                </p>

                <p>
                    From our kitchen to your table — we deliver happiness in every bite.
                </p>

                <div class="about-stats">
                    <div>
                        <h3>50+</h3>
                        <span>Recipes</span>
                    </div>
                    <div>
                         <h3>5★</h3>
                        <span>Reviews</span>
                    </div>
                    <div>
                        <h3>Fresh</h3>
                        <span>Daily Made</span>
                    </div>
                </div>

            </div>

        </div>
</section>

    
<button type="button" class="btn btn-brown btn-back-to-top" id="btn-back-to-top">
  <i class="fas fa-arrow-up"></i>
</button>

<?php include 'includes/footer.php';?>

