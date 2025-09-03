<x-app-layout>

<section class="blog-section sm:ml-64">
        <div class="blog-header">
            <h2 class="blog-title">รายวิชา</h2>
            <p class="blog-subtitle">Discover our latest insights, stories, and updates to help you stay informed and inspired.</p>
        </div>
        
        <div class="blog-posts">
            <!-- Blog Post 1 - Technology -->
            <article class="blog-card" onclick="window.location.href='{{ route('ห้องเรียน', ['id' => 5]) }}'">
                <img src="https://images.unsplash.com/photo-1518770660439-4636190af475?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80" 
                     alt="Circuit board with technology components" 
                     class="blog-card-image">
                <div class="blog-card-content">
                    <span class="blog-card-category">Technology</span>
                    <h3 class="blog-card-title">The Future of Web Development in 2023</h3>
                    <p class="blog-card-excerpt">Explore the emerging trends and technologies that are shaping the future of web development this year.</p>
                    <div class="blog-card-meta">
                        <span class="blog-card-date">June 15, 2023</span>
                        <span class="blog-card-author">
                            <img src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=688&q=80" 
                                 alt="Sarah Johnson" 
                                 class="author-avatar">
                            Sarah Johnson
                        </span>
                    </div>
                </div>
            </article>
            
            <!-- Blog Post 2 - Business -->
            <article class="blog-card">
                <img src="https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80" 
                     alt="Business team meeting" 
                     class="blog-card-image">
                <div class="blog-card-content">
                    <span class="blog-card-category" style="background: #48bb78;">Business</span>
                    <h3 class="blog-card-title">5 Strategies for Growing Your Online Business</h3>
                    <p class="blog-card-excerpt">Learn proven strategies to scale your online business and reach new customers effectively.</p>
                    <div class="blog-card-meta">
                        <span class="blog-card-date">June 10, 2023</span>
                        <span class="blog-card-author">
                            <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=687&q=80" 
                                 alt="Michael Chen" 
                                 class="author-avatar">
                            Michael Chen
                        </span>
                    </div>
                </div>
            </article>
            
            <!-- Blog Post 3 - Design -->
            <article class="blog-card">
                <img src="https://images.unsplash.com/photo-1541462608143-67571c6738dd?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80" 
                     alt="Designer working on wireframes" 
                     class="blog-card-image">
                <div class="blog-card-content">
                    <span class="blog-card-category" style="background: #9f7aea;">Design</span>
                    <h3 class="blog-card-title">UX Design Principles for Better Conversions</h3>
                    <p class="blog-card-excerpt">How applying fundamental UX principles can significantly improve your website's conversion rates.</p>
                    <div class="blog-card-meta">
                        <span class="blog-card-date">June 5, 2023</span>
                        <span class="blog-card-author">
                            <img src="https://images.unsplash.com/photo-1580489944761-15a19d654956?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=761&q=80" 
                                 alt="Emma Rodriguez" 
                                 class="author-avatar">
                            Emma Rodriguez
                        </span>
                    </div>
                </div>
            </article>
        </div>
        
        <div class="view-all-container">
            <a href="#" class="view-all-btn">View All Articles</a>
        </div>
    </section>
</x-app-layout>
<style>
        /* Main Blog Container */
        .blog-section {
            /* max-width: 1200px; */
            margin: 0 0 0 64 auto;
            padding: 4rem 2rem;
            /* font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; */
        }
        
        .blog-header {
            text-align: center;
            margin-bottom: 3rem;
        }
        
        .blog-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 1rem;
        }
        
        .blog-subtitle {
            font-size: 1.2rem;
            color: #4a5568;
            max-width: 700px;
            margin: 0 auto;
            line-height: 1.6;
        }
        
        /* Blog Posts Grid */
        .blog-posts {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 2rem;
        }
        
        /* Individual Blog Post Card */
        .blog-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .blog-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
        }
        
        .blog-card-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        
        .blog-card-content {
            padding: 1.5rem;
        }
        
        .blog-card-category {
            display: inline-block;
            background: #4299e1;
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 600;
            margin-bottom: 0.75rem;
        }
        
        .blog-card-title {
            font-size: 1.4rem;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 0.75rem;
            line-height: 1.3;
        }
        
        .blog-card-excerpt {
            color: #4a5568;
            margin-bottom: 1.25rem;
            line-height: 1.6;
        }
        
        .blog-card-meta {
            display: flex;
            align-items: center;
            font-size: 0.9rem;
            color: #718096;
        }
        
        .blog-card-date {
            margin-right: 1rem;
        }
        
        .blog-card-author {
            display: flex;
            align-items: center;
        }
        
        .author-avatar {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            margin-right: 0.5rem;
            object-fit: cover;
        }
        
        /* View All Button */
        .view-all-container {
            text-align: center;
            margin-top: 3rem;
        }
        
        .view-all-btn {
            display: inline-block;
            background: #4299e1;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 50px;
            font-weight: 600;
            text-decoration: none;
            transition: background 0.3s ease;
        }
        
        .view-all-btn:hover {
            background: #3182ce;
        }
        
        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .blog-section {
                padding: 3rem 1.5rem;
            }
            
            .blog-title {
                font-size: 2rem;
            }
            
            .blog-subtitle {
                font-size: 1.1rem;
            }
            
            .blog-posts {
                grid-template-columns: 1fr;
            }
        }
    </style>