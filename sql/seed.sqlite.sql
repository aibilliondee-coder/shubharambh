-- =============================================================================
-- Shubharambh Infra Advisors — SQLite Seed Data
-- Rich project details researched from developer sites + aggregators.
-- =============================================================================

-- -----------------------------------------------------------------------------
-- Site settings
-- -----------------------------------------------------------------------------
INSERT INTO site_settings
  (id, company_name, tagline, phone_primary, phone_whatsapp,
   email_primary, email_secondary, address_line, map_embed_url,
   rera_number, rera_notice,
   facebook_url, instagram_url, linkedin_url, youtube_url,
   hero_title, hero_subtitle, about_heading, about_body)
VALUES
  (1,
   'Shubharambh Infra Advisors',
   'Your Success Our Priority',
   '+91 9911600100',
   '919911600100',
   'company@shubharambhinfraadvisors.com',
   'support@shubharambhinfraadvisors.com',
   'B-220, Logix Technova, Sector 132, Noida – 201304, Uttar Pradesh, India',
   'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3502.2!2d77.386!3d28.502!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x390ce5be0e8f1111%3A0x0!2zQi0yMjAgTG9naXggVGVjaG5vdmEgU2VjdG9yIDEzMiBOb2lkYQ!5e0!3m2!1sen!2sin!4v1700000000000',
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
-- Projects (rich details)
-- -----------------------------------------------------------------------------

-- 1. M3M The Cullinan
INSERT INTO projects
  (slug, name, tagline, builder, location, city, price_display, property_type, configurations, sizes, possession, rera_id, description, amenities, connectivity, usps, cover_image, is_featured, is_active, sort_order)
VALUES
  ('m3m-the-cullinan',
   'M3M The Cullinan',
   'Ultra-luxury low-density residences',
   'M3M India',
   'Sector 94, Noida',
   'Noida',
   '₹8.8 Cr Onwards',
   'Luxury 3, 4 & 5 BHK Apartments',
   '3 BHK, 4 BHK and 5 BHK residences (two variants)',
   '3 BHK: 3,217 sq ft | 4 BHK: 4,315 sq ft | 5 BHK: 5,990–6,220 sq ft',
   'April 2028',
   'UPRERAPRJ442214',
   'M3M The Cullinan is an ultra-luxury low-density address in Sector 94, Noida spread across 12.8 acres. Just 374 curated residences across five G+33 towers, with only two apartments per floor for maximum privacy and panoramic views. Designer interiors, a grand clubhouse and 101 curated amenities define the lifestyle here.',
   '["Grand clubhouse","Infinity swimming pool","State-of-the-art gymnasium","Spa and wellness suites","Landscaped gardens","Jogging track","Indoor games zone","Kids play area","Yoga and meditation deck","24/7 security"]',
   '["Sector 94, Noida — gateway to Delhi via the DND Flyway","Close to Noida–Greater Noida Expressway","10 minutes to Sector 50 metro station","8–10 km from Noida Golf Course","Easy access to south Delhi"]',
   '["Only 374 residences across 12.8 acres — ultra-exclusive","Two apartments per floor for total privacy","101 curated lifestyle amenities","Five G+33 towers with panoramic views","Signature M3M craftsmanship and designer interiors"]',
   'projects/m3mcullinan-1.webp',
   1, 1, 10);

-- 2. M3M The Line
INSERT INTO projects
  (slug, name, tagline, builder, location, city, price_display, property_type, configurations, sizes, possession, rera_id, description, amenities, connectivity, usps, cover_image, is_featured, is_active, sort_order)
VALUES
  ('m3m-the-line',
   'M3M The Line',
   'High-street retail & pentsuites',
   'M3M India',
   'Sector 72, Noida',
   'Noida',
   '₹95 Lakh Onwards',
   'Retail Shops & Studio Pentsuites',
   'High-street retail shops, anchor spaces and studio pentsuites',
   'Retail shops ~505 sq ft carpet | Pentsuites ~865 sq ft carpet',
   'July 2028',
   'UPRERAPRJ246070',
   'M3M The Line in Sector 72, Central Noida is a premium mixed-use landmark featuring high-street retail, anchor brand spaces and compact studio pentsuites. Designed for investors who want assured rental income and lifestyle-driven destinations under one roof.',
   '["Basketball court","Jogging track","Swimming pools","Gymnasium","Tennis court","Billiards lounge","Fine-dining lounge","Entertainment arena","24/7 security","Landscaped plaza"]',
   '["Central Noida — Sector 72","Adjacent to Sector 72 commercial corridor","Easy reach of FNG Expressway","Smooth access to Delhi and Gurugram","Close to metro connectivity zone"]',
   '["Assured return 12–15% per annum on retail","7% assured lease on anchor spaces","Low 20% upfront payment plan","Signature M3M high-street format","Mixed-use retail + pentsuite investment"]',
   'projects/m3mtheline-1.webp',
   1, 1, 20);

-- 3. ONE FNG
INSERT INTO projects
  (slug, name, tagline, builder, location, city, price_display, property_type, configurations, sizes, possession, rera_id, description, amenities, connectivity, usps, cover_image, is_featured, is_active, sort_order)
VALUES
  ('one-fng',
   'ONE FNG',
   'Largest floor plates on the expressway',
   'Group 108',
   'Sector 142, Noida Expressway',
   'Noida',
   '₹1.15 Cr Onwards',
   'Grade-A Offices & Retail',
   'Grade-A office spaces and high-street retail (two towers, G+37 and G+15)',
   'Floor plate ~53,000 sq ft | Offices 3,200–3,500 sq ft | Retail variable',
   '13 August 2028',
   'UPRERAPRJ279516',
   'ONE FNG by Group 108 is a flagship commercial landmark on the Noida Expressway offering Grade-A office spaces and high-street retail. IGBC Platinum pre-certified, with the largest office floor plates in Noida (~53,000 sq ft) and 62.5% space efficiency. Two-tower configuration (G+37 and G+15) supports scalable footprints for corporates of every size.',
   '["State-of-the-art gymnasium","Indoor swimming pool","Meditation centre","Indoor games lounge","Multi-level parking","Landscaped gardens with fountains","Cycling & jogging tracks","Business centre","21 high-speed elevators","Food court and café"]',
   '["Directly on the Noida Expressway","2 km from Sector 142 metro station","3 km from Sector 143 metro station","Direct link to Greater Noida industrial zone","~35 km from IGI Airport (~1 hr)"]',
   '["Largest office floor plates in Noida (~53,000 sq ft)","IGBC Platinum Pre-Certified","62.5% space efficiency","Two-tower scalable configuration","Expressway visibility + corporate catchment"]',
   'projects/parasavenue1.webp',
   1, 1, 30);

-- 4. Eternia
INSERT INTO projects
  (slug, name, tagline, builder, location, city, price_display, property_type, configurations, sizes, possession, rera_id, description, amenities, connectivity, usps, cover_image, is_featured, is_active, sort_order)
VALUES
  ('eternia',
   'Eternia',
   'Intelligent 3 & 4 BHK layouts',
   'Great Value Realty & Yatharth Group',
   'Tech Zone IV, Greater Noida West',
   'Greater Noida',
   '₹1.77 Cr Onwards',
   'Luxury 3 & 4 BHK Apartments',
   '3 BHK, 3 BHK + Study, 4 BHK + Study',
   '3 BHK: 1,932 sq ft | 3 BHK + Study: 2,239 sq ft | 4 BHK + Study: 2,625 sq ft',
   'Not listed',
   'Not listed',
   'Eternia is a premium residential address in Tech Zone IV, Greater Noida West. Six G+30 towers set across six beautifully landscaped acres, with 130-metre road frontage, intelligent layouts that prioritise usable area over carpet-area padding and an anthurium-flower inspired design philosophy. Delivered under a government-backed ASPIRE initiative.',
   '["Swimming pool","State-of-the-art gymnasium","Jogging track","Toddlers play area","Billiards room","Indoor games room","Yoga deck","Basketball court","Senior citizen garden","Banquet hall","Landscaped lawn"]',
   '["Tech Zone IV, Greater Noida West","130-metre road frontage","Proximity to upcoming business parks","Close to Noida–Greater Noida expressway network","Access to Greater Noida commercial centres"]',
   '["Government-backed ASPIRE initiative (court oversight)","Six G+30 towers on 6 landscaped acres","Four lifts per tower","Anthurium-inspired design philosophy","Lesser saleable, more usable area per unit"]',
   'projects/m3mmansion1.webp',
   1, 1, 40);

-- 5. Kutumbh City
INSERT INTO projects
  (slug, name, tagline, builder, location, city, price_display, property_type, configurations, sizes, possession, rera_id, description, amenities, connectivity, usps, cover_image, is_featured, is_active, sort_order)
VALUES
  ('kutumbh-city',
   'Kutumbh City',
   'Sacred-city plots with modern comforts',
   'Geeta Group',
   'Bahadrabad Road (NH-334), Haridwar',
   'Haridwar',
   '₹71.25 Lakh Onwards',
   'Residential Plots',
   'Freehold residential plots in a 90 Bigha master-planned township',
   'Base rate ₹6,200/sq ft | ₹55,800/sq yd',
   'Not listed',
   'UKREP11250000690',
   'Kutumbh City is a HRDA and RERA approved gated township on Bahadrabad Road, Haridwar with freehold residential plots, premium clubhouse, Olympic-size pool and five landscaped parks. Designed for families seeking the serenity of the Ganges with all modern conveniences — and for investors betting on the NH-334 growth corridor.',
   '["Premium clubhouse","Olympic-size swimming pool","Divine temple","Gym and yoga room","Billiards, table tennis and gaming zone","5 landscaped parks","Kids play area","24x7 CCTV gated security","Medical shop and retail","40 ft wide internal roads"]',
   '["NH-334 Delhi–Haridwar corridor","2 min to Patanjali Yogpeeth","2 min to Maxwell Hospital","25 min to IIT Roorkee","25 min to Har Ki Pauri"]',
   '["HRDA and RERA approved freehold plots","Bank financing from leading nationalised and private banks","Historical appreciation 15–18% in 24 months","Strategic NH-334 commercial corridor","Premium park-facing and corner plot options"]',
   'projects/city.webp',
   1, 1, 50);

-- 6. SVG Town Square
INSERT INTO projects
  (slug, name, tagline, builder, location, city, price_display, property_type, configurations, sizes, possession, rera_id, description, amenities, connectivity, usps, cover_image, is_featured, is_active, sort_order)
VALUES
  ('svg-town-square',
   'SVG Town Square',
   'Retail, office & studio destination',
   'SVG Group',
   'Alpha 2, Greater Noida',
   'Greater Noida',
   '₹30 Lakh Onwards',
   'Retail, Office & Studio Spaces',
   'High-street retail shops, office suites, food court and 1RK studios',
   'Studios ~695 sq ft | Shop & office units variable',
   'December 2028',
   'UPRERAPRJ629900',
   'SVG Town Square is a mixed-use commercial destination in Alpha 2, Greater Noida featuring high-street retail, office suites, a buzzing food court and compact studio apartments. A 23-year-old developer with 10+ delivered projects and 6,000+ satisfied customers — built on the promise of "On Time, Every Time".',
   '["High-street retail frontage","Premium office suites","Multi-cuisine food court","Ample surface and basement parking","24x7 power backup","CCTV surveillance","Landscaped common areas","Modern high-speed lifts"]',
   '["Steps from Alpha-1 and Delta-1 metro stations","Knowledge Park vicinity","Close to premier educational institutions","Access to Noida retail hubs","Well-connected to Delhi NCR via expressways"]',
   '["23+ years of SVG Group delivery track record","10+ completed projects and 6,000+ happy customers","Mixed-use model for diverse tenant base","Excellent metro connectivity","Phase-wise RERA approvals"]',
   'projects/twintowerdxp1.webp',
   1, 1, 60);

-- 7. Uniwest Aero Hub
INSERT INTO projects
  (slug, name, tagline, builder, location, city, price_display, property_type, configurations, sizes, possession, rera_id, description, amenities, connectivity, usps, cover_image, is_featured, is_active, sort_order)
VALUES
  ('uniwest-aero-hub',
   'Uniwest Aero Hub',
   'Commercial hub near Noida Airport',
   'Uniwest Infratech',
   'Sector 22D, Yamuna Expressway, Greater Noida',
   'Greater Noida',
   '₹30 Lakh Onwards',
   'Retail, Studio & Food Court',
   'Retail shops, furnished studios and food court',
   'Retail / Food Court ~477 sq ft | Studios ~475 sq ft | Range 434–1,873 sq ft',
   'Early 2028',
   'UPRERAPRJ621735',
   'Uniwest Aero Hub is a premium mixed-use commercial project on the Yamuna Expressway, just minutes from the upcoming Noida International (Jewar) Airport. Surrounded by corporate anchors like Infosys, Samsung and Patanjali, with an expected price appreciation trajectory already visible in 2025.',
   '["Landscaped green surroundings","Large dedicated car parking","24x7 CCTV surveillance","Earthquake-resistant design","24x7 power backup","Spacious modern lifts","Dedicated retail, dining and leisure zones"]',
   '["10 min to Noida International (Jewar) Airport","5 min to upcoming Film City","Adjacent to Infosys, Samsung and Patanjali campuses","Yamuna Expressway access to Delhi/Noida"]',
   '["Airport proximity driving future connectivity","Corporate cluster proximity increases footfall","Early 2028 possession","Mixed-use commercial format","Strong price appreciation already visible"]',
   'projects/mahindracodenamegreenlife1.webp',
   1, 1, 70);

-- 8. Uniwest Hub
INSERT INTO projects
  (slug, name, tagline, builder, location, city, price_display, property_type, configurations, sizes, possession, rera_id, description, amenities, connectivity, usps, cover_image, is_featured, is_active, sort_order)
VALUES
  ('uniwest-hub',
   'Uniwest Hub',
   'Expressway retail & studio destination',
   'Uniwest Infratech',
   'Sector 22D, Yamuna Expressway, Greater Noida',
   'Greater Noida',
   'On Request',
   'Retail, Studio & Food Court',
   'Retail shops, furnished studios and food court',
   '434–1,873 sq ft unit range',
   'Early 2028',
   'Not listed',
   'Uniwest Hub is a mixed-use commercial and furnished-studio address on the Yamuna Expressway, close to Noida International Airport. Earthquake-resistant construction, 24x7 backup and landscaped surroundings make it a reliable long-term investment play in the airport growth corridor.',
   '["Large surface car parking","24x7 power backup and surveillance","Earthquake-resistant construction","Spacious common areas","Landscaped green surroundings","Retail and dining zones"]',
   '["10 min to Noida International Airport","5 min to Film City","Adjacent to Infosys, Samsung and Patanjali campuses","Yamuna Expressway access"]',
   '["Airport-centric growth location","Mixed commercial + furnished studio mix","Corporate catchment ensures demand","Healthy appreciation trajectory"]',
   'projects/elanpresidential1.webp',
   1, 1, 80);

-- 9. Uniwest Arcade
INSERT INTO projects
  (slug, name, tagline, builder, location, city, price_display, property_type, configurations, sizes, possession, rera_id, description, amenities, connectivity, usps, cover_image, is_featured, is_active, sort_order)
VALUES
  ('uniwest-arcade',
   'Uniwest Arcade',
   'Premium retail & food destination',
   'Uniwest Infratech',
   'Sector 102, Noida–Bhangel–Dadri Road',
   'Noida',
   'On Request',
   'Retail Shops & Food Court',
   'Retail shops and food court counters',
   'Total project area ~2,751 sq m',
   'Not listed',
   'Not listed',
   'Uniwest Arcade is a high-street retail and food court destination on the Noida–Bhangel–Dadri road in Sector 102, serving a catchment of ~8 lakh residents. Directly connected to Sector 101 metro station with strong footfall from Noida''s premium residential sectors.',
   '["Spacious world-class retail environment","Premium food court with diverse cuisines","Modern kitchen equipment and appliances","Comfortable customer seating","Multiple food counters","Surface parking"]',
   '["Sector 101 metro station at the doorstep","Catchment of ~8 lakh residents","Close to Indira Gandhi International Airport","Prime residential sector proximity","Strategic main road location"]',
   '["Direct metro connectivity","8 lakh resident catchment","Prime residential sector footfall","Dedicated food court and retail mix"]',
   'projects/elanimperial1.webp',
   1, 1, 90);

-- 10. Shubh Kadam
INSERT INTO projects
  (slug, name, tagline, builder, location, city, price_display, property_type, configurations, sizes, possession, rera_id, description, amenities, connectivity, usps, cover_image, is_featured, is_active, sort_order)
VALUES
  ('shubh-kadam',
   'Shubh Kadam',
   'Boutique retreat near Jim Corbett',
   'Globalbirth Developers',
   'Dhela, Ramnagar (Jim Corbett), Uttarakhand',
   'Ramnagar',
   'On Request',
   'Studio Apartments & Duplex Cottages',
   '20 fully furnished studios + 10 duplex cottages (Phase 2)',
   'Total area ~13,616 sq ft',
   'Not listed',
   'Not listed',
   'Shubh Kadam is a boutique eco-retreat minutes from the Dhela gate of Jim Corbett National Park. Fully furnished studio apartments and duplex cottages designed for nature lovers and hospitality investors — eco-friendly technologies, sustainable materials and a rental-ready format.',
   '["Fully furnished studio units","Spacious duplex cottages","Eco-friendly technologies and materials","Landscaped green surroundings","Walking distance to Dhela gate","Managed commercial retail spaces","Peaceful natural ambience"]',
   '["Minutes from Jim Corbett National Park","Direct access to the Dhela gate","Close to wildlife sanctuaries","Scenic mountain surroundings","On key tourism routes"]',
   '["Limited-inventory boutique scale","Eco-tourism and wildlife photography market","Bank financing available through partnerships","Hospitality-ready rental opportunity"]',
   'projects/civitechsantoni1.webp',
   1, 1, 100);

-- 11. Corbett Eye
INSERT INTO projects
  (slug, name, tagline, builder, location, city, price_display, property_type, configurations, sizes, possession, rera_id, description, amenities, connectivity, usps, cover_image, is_featured, is_active, sort_order)
VALUES
  ('corbett-eye',
   'Corbett Eye',
   'Luxury plots with a nature view',
   'Globalbirth Developers',
   'Ramnagar – Jim Corbett NH, Ramnagar, Uttarakhand',
   'Ramnagar',
   'On Request',
   'Residential Plots & Villas',
   'Luxury residential plots in a 8.5-acre gated township',
   'Total township ~8.5 acres',
   'Not listed',
   'Not listed',
   'Corbett Eye is a luxury plotted development on the Ramnagar–Jim Corbett National Highway. Solar-powered, rainwater-harvested, fully gated and wrapped in landscaped gardens — a second-home address at the edge of the national park, ideal for holiday retreats and long-term wealth creation.',
   '["Clubhouse with premium facilities","Landscaped gardens and fountains","Wide internal roads","24x7 surveillance","Gated community with dedicated staff","Ample parking","Reliable water and power","Rainwater harvesting","Solar power utilisation","Community gardens"]',
   '["Ramnagar – Jim Corbett NH frontage","Dalpatpur–Kashipur Highway access","Minutes from Jim Corbett National Park","Easy access to neighbouring towns","Healthcare, schools and entertainment nearby"]',
   '["Eco-friendly infrastructure (solar + rainwater)","Gated community with 24/7 security","Second-home and investment play","National-park adjacency"]',
   'projects/godrejriverine1.webp',
   1, 1, 110);

-- -----------------------------------------------------------------------------
-- Partners
-- -----------------------------------------------------------------------------
INSERT INTO partners (name, logo, website, is_active, sort_order) VALUES
('M3M Group',     'partners/m3m.png',         'https://www.m3mindia.com', 1, 10),
('SVG Group',     'partners/svg.png',         NULL,                       1, 20),
('Global Birth',  'partners/globalbirth.png', NULL,                       1, 30),
('Uniwest',       'partners/uniwest.png',     NULL,                       1, 40),
('Group 108',     'partners/group108.jpg',    NULL,                       1, 50);

-- -----------------------------------------------------------------------------
-- Testimonials
-- -----------------------------------------------------------------------------
INSERT INTO testimonials (client_name, city, rating, quote, is_active, sort_order) VALUES
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
INSERT INTO team_members (full_name, title, bio, photo, sort_order) VALUES
('Mr. Mohit Khari', 'Founder & CEO',
 'With over a decade of rich experience in Indian real estate, Mr. Mohit Khari leads Shubharambh Infra Advisors with a mission to simplify property buying, selling and investing for every client. Under his leadership, the firm has built a reputation for transparent advice, deep market insight and long-term client relationships.',
 'team/mohit-khari.jpg', 10);

-- -----------------------------------------------------------------------------
-- New Projects — Session 3 (19 April 2026)
-- -----------------------------------------------------------------------------
INSERT OR IGNORE INTO projects
  (slug, name, tagline, builder, location, city, price_display, property_type,
   configurations, sizes, possession, rera_id, description, amenities,
   connectivity, usps, cover_image, is_featured, is_active, sort_order)
VALUES
('tnt-the-blue',
 'T&T The Blue',
 'AI-Enabled Luxury Living on NH-24, Ghaziabad',
 'T&T Group',
 'Siddharth Vihar, NH-24, Ghaziabad',
 'Ghaziabad',
 '₹2.8 Cr Onwards',
 'Luxury 3 BHK Apartments',
 '3 BHK',
 '3 BHK: 2,048 sq ft',
 'December 2027',
 'UPRERAPRJ899584',
 'T&T The Blue is a landmark luxury residential project in Siddharth Vihar, Ghaziabad — strategically located on NH-24 with seamless connectivity to Noida Sector 62, Akshardham and Delhi. Developed by T&T Group, The Blue redefines premium urban living with AI-enabled smart homes, fully loaded apartments and world-class amenities. With only 2 residences per floor, it offers an ultra-exclusive, zero-traffic, pedestrian-friendly community wrapped in stunning white architecture and lush green landscaping.',
 '["World-class swimming pool","Indoor clubhouse","Yoga lawn & sensory zen garden","Kids garden & play area","Multi-purpose sports courts","Open-air gymnasium","Jogging & cycling track","Daily needs kiosk","5-tier security system","Zero on-ground traffic design","Grand 16-foot lobby","Digital amenities & smart home"]',
 '["NH-24 highway directly accessible","5 minutes from Noida Sector 62","10 minutes from Akshardham Temple","6 minutes to Fortis Hospital Sector 62","20 minutes to Sector 18 market","30 minutes to Connaught Place","40 minutes to New Delhi Railway Station","50 minutes to IGI Airport"]',
 '["AI-enabled smart home technology","Fully loaded luxury apartments — move-in ready","Only 2 residences per floor — ultra-exclusive living","6 customizable design themes (Classical, Contemporary, Futuristic)","All-white façade with lush green landscaping","RERA registered — UPRERAPRJ899584","Zero on-ground traffic — complete pedestrian zone","Sustainable & ecological architecture"]',
 'projects/tnt-the-blue.webp',
 1, 1, 125),

('yatharth-highlife',
 'Yatharth HighLife TechZone 4',
 'IKEA-Furnished Smart Homes by NBCC, Greater Noida West',
 'Yatharth Group & NBCC India',
 'Tech Zone IV, Dream Valley, Greater Noida West',
 'Greater Noida',
 '₹90 Lakh Onwards',
 'Smart 1 BHK & 2 BHK Apartments',
 '1 BHK, 2 BHK',
 '1 BHK: 941–964 sq ft | 2 BHK: 1,410–1,454 sq ft',
 '2030',
 'NBCC Dream Valley — SC supervised',
 'Yatharth HighLife TechZone 4 is a premium residential development in Greater Noida West, built under the supervision of NBCC India Ltd. — a Government of India undertaking — and approved by the Supreme Court of India. Offering 1 BHK and 2 BHK IKEA-furnished smart apartments, Yatharth HighLife brings together affordability, modern design and technology in one of Delhi NCR''s fastest-growing micro-markets.',
 '["Infinity swimming pool","Fully equipped gymnasium","Kids play zone","Jogging & cycling tracks","Landscaped gardens","IKEA-furnished interiors (standard)","Smart home integration","24x7 CCTV surveillance","24x7 concierge services","Power backup","Rainwater harvesting","Commercial podium","Dedicated parking"]',
 '["FNG Expressway — 10-15 minutes","Ryan International School — 0.6 km","Gaur City Mall — 2.4 km","Proposed metro station — nearby","Noida Expressway — 15 minutes","Jewar International Airport — 35-40 km"]',
 '["NBCC India Ltd. supervised — Supreme Court approved","IKEA-furnished apartments — India first of its kind","Smart home integration standard","Pre-launch offer: 10% now, no payment for 24 months","Only 91 units across 2 towers — boutique community","Backed by NBCC (Government of India undertaking)"]',
 'projects/yatharth-highlife.webp',
 1, 1, 120);
