-- =============================================================================
-- Shubharambh Infra Advisors — Seed Data
-- Run after schema.sql on a fresh database.
-- =============================================================================

SET NAMES utf8mb4;

-- -----------------------------------------------------------------------------
-- Site settings
-- -----------------------------------------------------------------------------
INSERT INTO `site_settings`
  (`id`, `company_name`, `tagline`, `phone_primary`, `phone_whatsapp`,
   `email_primary`, `email_secondary`, `address_line`, `map_embed_url`,
   `rera_number`, `rera_notice`,
   `facebook_url`, `instagram_url`, `linkedin_url`, `youtube_url`,
   `hero_title`, `hero_subtitle`, `about_heading`, `about_body`)
VALUES
  (1,
   'Shubharambh Infra Advisors',
   'Your Success Our Priority',
   '+91 9911600100',
   '919911600100',
   'company@shubharambhinfraadvisors.com',
   'support@shubharambhinfraadvisors.com',
   'B-220, Logix Technova, Sector 132, Noida – 201304, Uttar Pradesh, India',
   'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3503.916!2d77.386!3d28.502!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMjjCsDMwJzA3LjIiTiA3N8KwMjMnMDkuNiJF!5e0!3m2!1sen!2sin!4v1700000000000',
   'UP RERA: Coming Soon',
   'Shubharambh Infra Advisors is a RERA-registered real estate consultancy. Property listings, pricing and availability are indicative and subject to change as per developer terms.',
   'https://www.facebook.com/shubharambhinfraadvisors',
   'https://www.instagram.com/shubharambh_infra_advisors',
   'https://www.linkedin.com/company/shubharambh-infra-advisors',
   'https://www.youtube.com/@ShubharambhInfraAdvisors',
   'Find Your Luxury Home',
   'BEST REAL ESTATE PROPERTY CONSULTANT IN DELHI/NCR',
   'Get To Know About Shubharambh Infra',
   'Shubharambh Infra Advisors is a trusted, RERA-registered real estate consulting firm headquartered in Noida. Led by Mr. Mohit Khari, the firm brings over a decade of rich experience in Indian real estate, helping clients buy, sell and invest in residential and commercial properties across Delhi NCR and Uttarakhand. Our mission is to simplify every step of the property journey while ensuring transparency, integrity and measurable value for every client.');

-- -----------------------------------------------------------------------------
-- Projects
-- -----------------------------------------------------------------------------
INSERT INTO `projects`
  (`slug`, `name`, `builder`, `location`, `city`, `price_display`, `property_type`, `description`, `cover_image`, `is_featured`, `is_active`, `sort_order`)
VALUES
  ('kutumbh-city', 'Kutumbh City', 'Geeta Group',
   'Bahadrabad Road, Haridwar', 'Haridwar',
   '₹71.25 Lacs Onwards', 'Residential Plots',
   'RERA-approved and HRDA-approved residential plots on Bahadrabad Road, Haridwar. A serene address for families seeking a tranquil lifestyle with modern amenities.',
   'projects/kutumbh-city.jpg', 1, 1, 10),

  ('svg-town-square', 'SVG Town Square', 'SVG Group',
   'Alpha 2, Greater Noida', 'Greater Noida',
   '₹30 Lacs Onwards', 'Retail & Office Spaces',
   'Premium retail and office spaces in Alpha 2, Greater Noida. Designed for discerning investors and business owners who demand visibility and footfall.',
   'projects/svg-town-square.jpg', 1, 1, 20),

  ('m3m-the-line', 'M3M The Line', 'M3M Group',
   'Sector 72, Noida', 'Noida',
   '₹80 Lacs Onwards', 'Studio Apartments & Retail Shops',
   'A mixed-use landmark in Sector 72, Noida featuring studio apartments and high-street retail shops, backed by M3M Group’s signature quality.',
   'projects/m3m-the-line.jpg', 1, 1, 30),

  ('m3m-the-cullinan', 'M3M The Cullinan', 'M3M Group',
   'Sector 94, Noida', 'Noida',
   '₹1 Cr Onwards', 'Luxury 3, 4 & 5 BHK Apartments',
   'Ultra-luxury 3, 4 and 5 BHK residences by M3M in Sector 94, Noida. Designer interiors, panoramic views and world-class amenities.',
   'projects/m3m-the-cullinan.jpg', 1, 1, 40),

  ('uniwest-aero-hub', 'Uniwest Aero Hub', 'Uniwest',
   'Sector 22D, Yamuna Expressway', 'Greater Noida',
   'On Request', 'Commercial',
   'A strategically located commercial project along the Yamuna Expressway, close to Noida International Airport. Ideal for long-term capital appreciation.',
   'projects/uniwest-aero-hub.jpg', 1, 1, 50),

  ('uniwest-hub', 'Uniwest Hub', 'Uniwest',
   'Sector 22D, Yamuna Expressway', 'Greater Noida',
   'On Request', 'Commercial',
   'Grade-A commercial development on the Yamuna Expressway offering high-visibility retail and office spaces.',
   'projects/uniwest-hub.jpg', 1, 1, 60),

  ('eternia', 'Eternia', 'Great Value Realty & Yatharth Group',
   'Tech Zone 4, Greater Noida West', 'Greater Noida',
   'On Request', 'Residential',
   'Contemporary residential address in Tech Zone 4, Greater Noida West with excellent connectivity and lifestyle amenities.',
   'projects/eternia.jpg', 1, 1, 70),

  ('uniwest-arcade', 'Uniwest Arcade', 'Uniwest',
   'Sector 102, Noida', 'Noida',
   'On Request', 'Commercial',
   'Premium commercial arcade in the rapidly growing Sector 102, Noida corridor. Designed for retail and F&B brands.',
   'projects/uniwest-arcade.jpg', 1, 1, 80),

  ('shubh-kadam', 'Shubh Kadam', 'Globalbirth Group',
   'Ramnagar, Uttarakhand', 'Ramnagar',
   'On Request', 'Studio Apartments & Duplex Cottages',
   'Boutique studio apartments and duplex cottages near Jim Corbett National Park, Ramnagar. A signature getaway investment.',
   'projects/shubh-kadam.jpg', 1, 1, 90),

  ('corbett-eye', 'Corbett Eye', 'Globalbirth Group',
   'Ramnagar, Uttarakhand', 'Ramnagar',
   'On Request', 'Residential',
   'A nature-inspired residential development at the edge of Jim Corbett, Ramnagar. Perfect second homes and holiday retreats.',
   'projects/corbett-eye.jpg', 1, 1, 100),

  ('one-fng', 'ONE FNG', 'Group 108',
   'Sector 142, Noida', 'Noida',
   '₹1.15 Cr Onwards', 'Commercial',
   'Iconic Grade-A commercial tower in Sector 142, Noida on the Noida-Greater Noida Expressway. Signature address for corporates.',
   'projects/one-fng.jpg', 1, 1, 110);

-- -----------------------------------------------------------------------------
-- Developer partners
-- -----------------------------------------------------------------------------
INSERT INTO `partners` (`name`, `logo`, `website`, `is_active`, `sort_order`) VALUES
  ('M3M Group',     'partners/m3m.png',         'https://www.m3mindia.com',     1, 10),
  ('SVG Group',     'partners/svg.png',         NULL,                           1, 20),
  ('Global Birth',  'partners/globalbirth.png', NULL,                           1, 30),
  ('Uniwest',       'partners/uniwest.png',     NULL,                           1, 40),
  ('Group 108',     'partners/group108.jpg',    NULL,                           1, 50);

-- -----------------------------------------------------------------------------
-- Testimonials (initials only; content paraphrased from original site)
-- -----------------------------------------------------------------------------
INSERT INTO `testimonials` (`client_name`, `city`, `rating`, `quote`, `is_active`, `sort_order`) VALUES
  ('A. Gupta', 'Noida', 5,
   'The experience with Shubharambh Infra was truly great. The team is highly professional and guided my investment plans expertly, every step of the way.',
   1, 10),
  ('R. Verma', 'Gurgaon', 5,
   'An amazing experience working with Shubharambh Infra. They explained the Gurgaon market to me in depth and helped me shortlist the right options.',
   1, 20),
  ('S. Iyer', 'Delhi', 5,
   'I truly appreciate the research and dedication. They helped my sister purchase two plots and a commercial shop with complete clarity on every document.',
   1, 30),
  ('N. Khan', 'Noida', 5,
   'My experience with Shubharambh Infra was exceptional. The team guided me seamlessly and the after-sales support has been genuinely helpful.',
   1, 40),
  ('P. Mehta', 'Ghaziabad', 5,
   'Excellent experience — a trustworthy company and a true one-stop shop for all of my real estate needs. Every team member cares for the client like family.',
   1, 50);

-- -----------------------------------------------------------------------------
-- Team members
-- -----------------------------------------------------------------------------
INSERT INTO `team_members` (`full_name`, `title`, `bio`, `photo`, `sort_order`) VALUES
  ('Mr. Mohit Khari', 'Founder & CEO',
   'With over a decade of rich experience in Indian real estate, Mr. Mohit Khari leads Shubharambh Infra Advisors with a mission to simplify property buying, selling and investing for every client. Under his leadership, the firm has built a reputation for transparent advice, deep market insight and long-term client relationships.',
   'team/mohit-khari.jpg', 10);
