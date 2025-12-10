<?php
// Language Configuration File
// includes/language.php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Set default language
if (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = 'en';
}

// Handle language switch
if (isset($_GET['lang']) && in_array($_GET['lang'], ['en', 'ne'])) {
    $_SESSION['lang'] = $_GET['lang'];
}

$lang = $_SESSION['lang'];

// Language translations
$translations = [
    'en' => [
        // Header & Navigation
        'municipality_title' => 'Mechinagar Municipality, Office of The Municipal Executive',
        'municipality_subtitle' => 'Koshi Province, Government of Nepal',
        'complaint_system' => 'Complaint Management System',
        'dashboard' => 'Dashboard',
        'new_complaint' => 'New Complaint',
        'my_complaints' => 'My Complaints',
        'feedback' => 'Feedback',
        'profile' => 'Profile',
        'logout' => 'Logout',
        'citizen_login' => 'Citizen Login',
        'register' => 'Register',
        'admin_login' => 'Admin',
        'statistics' => 'Statistics',
        'manage_complaints' => 'Manage Complaints',
        'view_feedback' => 'View Feedback',
        
        // Homepage
        'main_title' => 'Citizen Complaint Management System',
        'main_subtitle' => 'Report civic issues, track complaints, and help improve our community',
        'for_citizens' => 'For Citizens',
        'for_administrators' => 'For Administrators',
        'citizens_desc' => 'Submit complaints, track their status, and provide feedback on resolved issues',
        'admin_desc' => 'Manage complaints, view feedback, and monitor system statistics',
        'login' => 'Login',
        'admin_login_btn' => 'Admin Login',
        
        // Features
        'system_features' => 'System Features',
        'features_subtitle' => 'Efficient complaint management for better governance',
        'submit_complaints' => 'Submit Complaints',
        'submit_desc' => 'Easy-to-use interface for reporting civic issues with photo evidence',
        'track_status' => 'Track Status',
        'track_desc' => 'Real-time tracking of complaint status from pending to resolved',
        'give_feedback' => 'Give Feedback',
        'feedback_desc' => 'Rate and review the resolution process to improve services',
        'view_statistics' => 'View Statistics',
        'stats_desc' => 'Comprehensive analytics and reports for administrators',
        
        // Login & Register
        'citizen_login_title' => 'Citizen Login',
        'login_welcome' => 'Welcome back! Please login to your account',
        'email_address' => 'Email Address',
        'password' => 'Password',
        'forgot_password' => 'Forgot Password?',
        'dont_have_account' => "Don't have an account?",
        'register_here' => 'Register here',
        'back_to_home' => 'Back to Home',
        'admin_login_title' => 'Administrator Login',
        'admin_welcome' => 'Secure access to system management',
        'username' => 'Username',
        'login_as_admin' => 'Login as Administrator',
        'for_citizens_note' => 'For Citizens: Please use the',
        'citizen_login_page' => 'Citizen Login',
        
        // Register
        'citizen_registration' => 'Citizen Registration',
        'create_account' => 'Create your account to get started',
        'full_name' => 'Full Name',
        'confirm_password' => 'Confirm Password',
        'already_have_account' => 'Already have an account?',
        'login_here' => 'Login here',
        
        // Dashboard
        'welcome' => 'Welcome',
        'total_complaints' => 'Total',
        'pending' => 'Pending',
        'in_progress' => 'In Progress',
        'resolved' => 'Resolved',
        'submit_new_complaint' => 'Submit New Complaint',
        
        // Complaints
        'all_complaints' => 'All Complaints',
        'category' => 'Category',
        'description' => 'Description',
        'status' => 'Status',
        'image' => 'Image',
        'date' => 'Date',
        'actions' => 'Actions',
        'no_complaints' => 'No complaints found',
        
        // Submit Complaint
        'submit_complaint_title' => 'Submit New Complaint',
        'select_category' => '-- Select Category --',
        'road_maintenance' => 'Road Maintenance',
        'street_light' => 'Street Light',
        'water_supply' => 'Water Supply',
        'sanitation' => 'Sanitation',
        'traffic' => 'Traffic',
        'parks' => 'Parks & Recreation',
        'other' => 'Other',
        'describe_complaint' => 'Describe your complaint in detail...',
        'upload_image' => 'Upload Image (Optional)',
        'max_size' => 'Max 5MB. Allowed: JPG, PNG, GIF',
        'submit' => 'Submit Complaint',
        
        // Feedback
        'submit_feedback' => 'Submit Feedback',
        'related_complaint' => 'Related Complaint (Optional)',
        'rating' => 'Rating',
        'select_rating' => '-- Select rating --',
        'poor' => 'Poor',
        'fair' => 'Fair',
        'good' => 'Good',
        'very_good' => 'Very Good',
        'excellent' => 'Excellent',
        'message' => 'Message',
        'share_feedback' => 'Share your feedback...',
        
        // Footer
        'all_rights_reserved' => 'All rights reserved',
        
        // Status
        'status_pending' => 'Pending',
        'status_progress' => 'In Progress',
        'status_resolved' => 'Resolved',
        
        // Common
        'required' => '*',
        'search' => 'Search',
        'filter' => 'Filter',
        'reset' => 'Reset',
        'close' => 'Close',
        'save' => 'Save',
        'cancel' => 'Cancel',
        'delete' => 'Delete',
        'edit' => 'Edit',
        'view' => 'View',
    ],
    
    'ne' => [
        // Header & Navigation
        'municipality_title' => 'मेचीनगर नगरपालिका, नगर कार्यपालिकाको कार्यालय',
        'municipality_subtitle' => 'कोशी प्रदेश, नेपाल सरकार',
        'complaint_system' => 'उजुरी व्यवस्थापन प्रणाली',
        'dashboard' => 'ड्यासबोर्ड',
        'new_complaint' => 'नयाँ उजुरी',
        'my_complaints' => 'मेरा उजुरीहरू',
        'feedback' => 'प्रतिक्रिया',
        'profile' => 'प्रोफाइल',
        'logout' => 'लगआउट',
        'citizen_login' => 'नागरिक लगइन',
        'register' => 'दर्ता गर्नुहोस्',
        'admin_login' => 'प्रशासक',
        'statistics' => 'तथ्याङ्क',
        'manage_complaints' => 'उजुरी व्यवस्थापन',
        'view_feedback' => 'प्रतिक्रिया हेर्नुहोस्',
        
        // Homepage
        'main_title' => 'नागरिक उजुरी व्यवस्थापन प्रणाली',
        'main_subtitle' => 'नागरिक समस्याहरू रिपोर्ट गर्नुहोस्, उजुरीहरू ट्र्याक गर्नुहोस्, र हाम्रो समुदाय सुधार गर्न मद्दत गर्नुहोस्',
        'for_citizens' => 'नागरिकहरूको लागि',
        'for_administrators' => 'प्रशासकहरूको लागि',
        'citizens_desc' => 'उजुरी पेश गर्नुहोस्, तिनीहरूको स्थिति ट्र्याक गर्नुहोस्, र समाधान भएका मुद्दाहरूमा प्रतिक्रिया दिनुहोस्',
        'admin_desc' => 'उजुरीहरू व्यवस्थापन गर्नुहोस्, प्रतिक्रिया हेर्नुहोस्, र प्रणाली तथ्याङ्क निगरानी गर्नुहोस्',
        'login' => 'लगइन',
        'admin_login_btn' => 'प्रशासक लगइन',
        
        // Features
        'system_features' => 'प्रणाली सुविधाहरू',
        'features_subtitle' => 'राम्रो शासनको लागि कुशल उजुरी व्यवस्थापन',
        'submit_complaints' => 'उजुरी पेश गर्नुहोस्',
        'submit_desc' => 'फोटो प्रमाणको साथ नागरिक समस्याहरू रिपोर्ट गर्न सजिलो इन्टरफेस',
        'track_status' => 'स्थिति ट्र्याक गर्नुहोस्',
        'track_desc' => 'विचाराधीन देखि समाधान सम्मको उजुरी स्थितिको वास्तविक समय ट्र्याकिङ',
        'give_feedback' => 'प्रतिक्रिया दिनुहोस्',
        'feedback_desc' => 'सेवाहरू सुधार गर्न समाधान प्रक्रियाको मूल्याङ्कन र समीक्षा गर्नुहोस्',
        'view_statistics' => 'तथ्याङ्क हेर्नुहोस्',
        'stats_desc' => 'प्रशासकहरूको लागि व्यापक विश्लेषण र रिपोर्टहरू',
        
        // Login & Register
        'citizen_login_title' => 'नागरिक लगइन',
        'login_welcome' => 'फेरि स्वागत छ! कृपया आफ्नो खातामा लगइन गर्नुहोस्',
        'email_address' => 'इमेल ठेगाना',
        'password' => 'पासवर्ड',
        'forgot_password' => 'पासवर्ड बिर्सनुभयो?',
        'dont_have_account' => 'खाता छैन?',
        'register_here' => 'यहाँ दर्ता गर्नुहोस्',
        'back_to_home' => 'गृहपृष्ठमा फर्कनुहोस्',
        'admin_login_title' => 'प्रशासक लगइन',
        'admin_welcome' => 'प्रणाली व्यवस्थापनमा सुरक्षित पहुँच',
        'username' => 'प्रयोगकर्ता नाम',
        'login_as_admin' => 'प्रशासकको रूपमा लगइन गर्नुहोस्',
        'for_citizens_note' => 'नागरिकहरूको लागि: कृपया प्रयोग गर्नुहोस्',
        'citizen_login_page' => 'नागरिक लगइन',
        
        // Register
        'citizen_registration' => 'नागरिक दर्ता',
        'create_account' => 'सुरु गर्न आफ्नो खाता सिर्जना गर्नुहोस्',
        'full_name' => 'पूरा नाम',
        'confirm_password' => 'पासवर्ड पुष्टि गर्नुहोस्',
        'already_have_account' => 'पहिले नै खाता छ?',
        'login_here' => 'यहाँ लगइन गर्नुहोस्',
        
        // Dashboard
        'welcome' => 'स्वागत छ',
        'total_complaints' => 'जम्मा',
        'pending' => 'विचाराधीन',
        'in_progress' => 'प्रगतिमा',
        'resolved' => 'समाधान भएको',
        'submit_new_complaint' => 'नयाँ उजुरी पेश गर्नुहोस्',
        
        // Complaints
        'all_complaints' => 'सबै उजुरीहरू',
        'category' => 'श्रेणी',
        'description' => 'विवरण',
        'status' => 'स्थिति',
        'image' => 'तस्वीर',
        'date' => 'मिति',
        'actions' => 'कार्यहरू',
        'no_complaints' => 'कुनै उजुरी फेला परेन',
        
        // Submit Complaint
        'submit_complaint_title' => 'नयाँ उजुरी पेश गर्नुहोस्',
        'select_category' => '-- श्रेणी चयन गर्नुहोस् --',
        'road_maintenance' => 'सडक मर्मत',
        'street_light' => 'सडक बत्ती',
        'water_supply' => 'खानेपानी आपूर्ति',
        'sanitation' => 'सरसफाई',
        'traffic' => 'यातायात',
        'parks' => 'पार्क र मनोरञ्जन',
        'other' => 'अन्य',
        'describe_complaint' => 'आफ्नो उजुरी विस्तारमा वर्णन गर्नुहोस्...',
        'upload_image' => 'तस्वीर अपलोड गर्नुहोस् (ऐच्छिक)',
        'max_size' => 'अधिकतम ५MB। अनुमति: JPG, PNG, GIF',
        'submit' => 'उजुरी पेश गर्नुहोस्',
        
        // Feedback
        'submit_feedback' => 'प्रतिक्रिया पेश गर्नुहोस्',
        'related_complaint' => 'सम्बन्धित उजुरी (ऐच्छिक)',
        'rating' => 'मूल्याङ्कन',
        'select_rating' => '-- मूल्याङ्कन चयन गर्नुहोस् --',
        'poor' => 'कमजोर',
        'fair' => 'सामान्य',
        'good' => 'राम्रो',
        'very_good' => 'धेरै राम्रो',
        'excellent' => 'उत्कृष्ट',
        'message' => 'सन्देश',
        'share_feedback' => 'आफ्नो प्रतिक्रिया साझा गर्नुहोस्...',
        
        // Footer
        'all_rights_reserved' => 'सबै अधिकार सुरक्षित',
        
        // Status
        'status_pending' => 'विचाराधीन',
        'status_progress' => 'प्रगतिमा',
        'status_resolved' => 'समाधान भएको',
        
        // Common
        'required' => '*',
        'search' => 'खोज्नुहोस्',
        'filter' => 'फिल्टर',
        'reset' => 'रिसेट',
        'close' => 'बन्द गर्नुहोस्',
        'save' => 'सेभ गर्नुहोस्',
        'cancel' => 'रद्द गर्नुहोस्',
        'delete' => 'मेटाउनुहोस्',
        'edit' => 'सम्पादन गर्नुहोस्',
        'view' => 'हेर्नुहोस्',
    ]
];

// Function to get translation
function t($key) {
    global $translations, $lang;
    return $translations[$lang][$key] ?? $key;
}

// Function to get current language
function getCurrentLang() {
    return $_SESSION['lang'] ?? 'en';
}
?>