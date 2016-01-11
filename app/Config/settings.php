<?php

$config['S'] = [
    'Menus' => [
        [ // Du lịch
            'name' => __('nav_tours'),
            'icon' => 'fa fa-image',
            'child' => [
                [ // Địa danh
                    'name' => __('country_nav_title'),
                    'controller' => 'Countries',
                    'action' => 'index'
                ],
                [ // Địa danh
                    'name' => __('region_nav_title'),
                    'controller' => 'Regions',
                    'action' => 'index'
                ],
                [ // Địa điểm
                    'name' => __('place_nav_title'),
                    'controller' => 'Places',
                    'action' => 'index'
                ],
                [ // Cẩm nang du lịch
                    'name' => __('guide_nav_title'),
                    'controller' => 'Guides',
                    'action' => 'index'
                ],
                [ // Mẹo du lịch
                    'name' => __('tip_nav_title'),
                    'controller' => 'Tips',
                    'action' => 'index'
                ],
                [ // Tour du lịch
                    'name' => __('tour_nav_title'),
                    'controller' => 'Tours',
                    'action' => 'index'
                ]
            ]
        ],
        [ // Tin tức
            'name' => __('nav_news_category'),
            'icon' => 'fa fa-newspaper-o',
            'child' => [
                [ // Tin tức
                    'name' => __('new_title'),
                    'controller' => 'News',
                    'action' => 'index'
                ],
                [ // Hoạt động sự kiện
                    'name' => __('event_nav_title'),
                    'controller' => 'Events',
                    'action' => 'index'
                ]
            ]
        ],
        [ // Địa điểm
            'name' => __('nav_location'),
            'icon' => 'fa fa-building',
            'child' => [
                [ // Khách sạn
                    'name' => __('hotel_nav_title'),
                    'controller' => 'Hotels',
                    'action' => 'index'
                ],
                [ // Nhà hàng
                    'name' => __('restaurant_nav_title'),
                    'controller' => 'Restaurants',
                    'action' => 'index'
                ],
            ]
        ],
        [ // Danh muc
            'name' => __('category_nav_title'),
            'icon' => 'fa fa-folder-open-o',
            'child' => [
                [ // Region
                    'name' => __('region_title'),
                    'controller' => 'Categories',
                    'action' => 'index',
                    '?' => array(
                        'object_type_code' => 'regions'
                    )
                ],
                [ // Dia diem
                    'name' => __('place_nav_title'),
                    'controller' => 'Categories',
                    'action' => 'index',
                    '?' => array(
                        'object_type_code' => 'places'
                    )
                ],
                [ // Tours
                    'name' => __('tour_nav_title'),
                    'controller' => 'Categories',
                    'action' => 'index',
                    '?' => array(
                        'object_type_code' => 'tours'
                    )
                ],
                [ // Event
                    'name' => __('event_nav_title'),
                    'controller' => 'Categories',
                    'action' => 'index',
                    '?' => array(
                        'object_type_code' => 'events'
                    )
                ],
                [ // hotels
                    'name' => __('hotel_nav_title'),
                    'controller' => 'Categories',
                    'action' => 'index',
                    '?' => array(
                        'object_type_code' => 'hotels'
                    )
                ],
                [ // Restaurants
                    'name' => __('restaurant_nav_title'),
                    'controller' => 'Categories',
                    'action' => 'index',
                    '?' => array(
                        'object_type_code' => 'restaurants'
                    )
                ]
            ]
        ],
        [ // Collection
            'name' => __('Collections'),
            'icon' => 'fa fa-folder-open-o',
            'child' => [
                [ // Region
                    'name' => __('place_nav_title'),
                    'controller' => 'Collections',
                    'action' => 'index',
                    '?' => array(
                        'object_type_code' => 'places'
                    )
                ]
            ]
        ],
        [ // Topics
            'name' => __('Topics'),
            'icon' => 'fa fa-comments-o',
            'child' => [
                [ // places
                    'name' => __('place_nav_title'),
                    'controller' => 'Topics',
                    'action' => 'index',
                    '?' => array(
                        'object_type_code' => 'places'
                    )
                ],
                [ // restaurants
                    'name' => __('Restaurant foods'),
                    'controller' => 'Topics',
                    'action' => 'index',
                    '?' => array(
                        'object_type_code' => 'restaurant_foods'
                    )
                ]
            ]
        ],
        [ // Tiện ích
            'name' => __('facility_nav_title'),
            'icon' => 'fa fa-gears',
            'child' => [
                [ // Ngân hàng
                    'name' => __('bank_title'),
                    'controller' => 'Banks',
                    'action' => 'index'
                ],
                [ // Bệnh viện
                    'name' => __('hospital_nav_title'),
                    'controller' => 'Hospitals',
                    'action' => 'index'
                ],
                [ // Taxi
                    'name' => __('taxi_nav_title'),
                    'controller' => 'Taxi',
                    'action' => 'index'
                ],
                [ // Tau hoa
                    'name' => __('train_nav_title'),
                    'controller' => 'Trains',
                    'action' => 'index'
                ],
                [ // Bus
                    'name' => __('bus_nav_title'),
                    'controller' => 'Buses',
                    'action' => 'index'
                ],
                [ // Tau Thuyen
                    'name' => __('ship_nav_title'),
                    'controller' => 'Ships',
                    'action' => 'index'
                ],
                [ // Số điện thoại khẩn cấp
                    'name' => __('emergency_nav_title'),
                    'controller' => 'Emergencies',
                    'action' => 'index'
                ]
            ]
        ],
        [ // Liên kết
            'name' => __('nav_links'),
            'icon' => 'fa fa-link',
            'child' => [
                [ // Nghệ thuật dân gian
                    'name' => __('art_title'),
                    'controller' => 'VodAssets',
                    'action' => 'index'
                ],
                [ // Clip âm nhạc dân gian
                    'controller' => 'VodEpisodes',
                    'action' => 'index',
                    'name' => __('clip_music_title'),
                ],
                [ // ý tưởng giao thông
                    'controller' => 'TrafficVisitorIdeas',
                    'action' => 'index',
                    'name' => __('traffic_idea_title')
                ],
                [ // Biển báo giao thông
                    'controller' => 'TrafficSigns',
                    'action' => 'index',
                    'name' => __('traffic_signboards_title')
                ],
                [ // Thư viện giao thông
                    'controller' => 'TrafficLibraries',
                    'action' => 'index',
                    'name' => __('traffic_libs_title')
                ],
                [ // Phản ánh môi trường
                    'controller' => 'EnvironmentVisitorFeedbacks',
                    'action' => 'index',
                    'name' => __('env_feedback_title')
                ],
                [ // Đóng góp ý tưởng moi truong
                    'controller' => 'EnvironmentVisitorIdeas',
                    'action' => 'index',
                    'name' => __('idea_title')
                ]
            ]
        ],
        [ // Khuyen mai
            'name' => __('nav_promotion'),
            'icon' => 'fa fa-gift',
            'child' => [
                [ // Chuong trinh khuyen mai
                    'name' => __('promotion_title'),
                    'controller' => 'Promotions',
                    'action' => 'index'
                ],
                [ // Ma giam gia
                    'name' => __('coupon_title'),
                    'controller' => 'Coupons',
                    'action' => 'index'
                ]
            ]
        ],
        [ // Order
            'name' => __('nav_booking'),
            'icon' => 'fa fa-cart-arrow-down',
            'child' => [
                [ // Dat tour du lich
                    'name' => __('order_tours_title'),
                    'controller' => 'ImportHotels',
                    'action' => 'index'
                ],
                [ // Dat ve may bay
                    'controller' => 'ImportHotels',
                    'action' => 'index',
                    'name' => __('order_tour_reports_title'),
                ],
                [ // Dat nha hang
                    'controller' => 'ImportRestaurants',
                    'action' => 'index',
                    'name' => __('order_restaurants_title')
                ],
                [ // Dat khach san
                    'controller' => 'ImportHotels',
                    'action' => 'index',
                    'name' => __('order_hotels_title')
                ]
            ]
        ],
        [ // Game quiz
            'name' => __('win_nav_title'),
            'icon' => 'fa fa-gamepad',
            'child' => [
                [ // Quan ly player
                    'name' => __('player_nav_title'),
                    'controller' => 'Players',
                    'action' => 'index'
                ],
                [ // Daily report
                    'name' => __('daily_report_title'),
                    'controller' => 'DailyReports',
                    'action' => 'index'
                ],
                [ // Daily report
                    'name' => __('daily_general_report_title'),
                    'controller' => 'DailyReports',
                    'action' => 'general'
                ],
                [ // Quan ly cau hoi
                    'name' => __('question_group_nav_title'),
                    'controller' => 'QuestionGroups',
                    'action' => 'index'
                ],
                [ // Quan ly cau hoi
                    'name' => __('question_group_nav_title'),
                    'controller' => 'QuestionGroups',
                    'action' => 'index'
                ],
                [ // Danh muc cau hoi
                    'name' => __('question_category_nav_title'),
                    'controller' => 'QuestionCategories',
                    'action' => 'index'
                ],
                [ // Danh sach thue bao trung thuong
                    'controller' => 'Winner',
                    'action' => 'index',
                    'name' => __('win_title')
                ],
                [ // Chon thue bao trung giai theo ngay
                    'controller' => 'Winner',
                    'action' => 'chooseDaily',
                    'name' => __('poi_dai_title')
                ],
                [ // Chon thue bao trung giai theo tuan
                    'controller' => 'Winner',
                    'action' => 'chooseWeekly',
                    'name' => __('poi_week_title')
                ],
                [ // Chon thue bao trung giai theo thang
                    'controller' => 'Winner',
                    'action' => 'chooseMonthly',
                    'name' => __('poi_month_title')
                ],
            ]
        ],
        [ // Blacklist
            'name' => __('Blacklist'),
            'icon' => 'fa fa-ban',
            'child' => [
                [ // blacklist
                    'controller' => 'VisitorBlacklists',
                    'action' => 'index',
                    'name' => __('Blacklist')
                ],
                [ // csv
                    'controller' => 'VisitorBlacklists',
                    'action' => 'processCsv',
                    'name' => __('Xử lý theo file')
                ],
                [ // import list
                    'controller' => 'VisitorBlacklists',
                    'action' => 'csvImportList',
                    'name' => __('Lịch sử xử lý file')
                ],
            ]
        ],
        [ // Lich su su dung
            'name' => __('Lịch sử sử dụng'),
            'icon' => 'fa fa-history',
            'child' => [
                [ // Đăng ký, hủy
                    'name' => __('Lịch sử đăng ký, hủy'),
                    'controller' => 'Histories',
                    'action' => 'index'
                ],
                [ // SMS
                    'name' => __('Lịch sử SMS'),
                    'controller' => 'Histories',
                    'action' => 'smsHis'
                ],
                [ // Lịch sử sử dụng
                    'name' => __('Lịch sử sử dụng'),
                    'controller' => 'Histories',
                    'action' => 'useHis'
                ],
                [ // Lịch sử trừ cước
                    'name' => __('Lịch sử trừ cước'),
                    'controller' => 'Histories',
                    'action' => 'chargeHis'
                ],
                [ // Lịch sử tác động
                    'name' => __('Lịch sử tác động'),
                    'controller' => 'Histories',
                    'action' => 'actionHis'
                ],
            ]
        ],
        [ // Export
            'name' => __('Export dữ liệu'),
            'icon' => 'fa fa-tasks',
            'child' => [
                [ // Đăng ký
                    'name' => __('Danh sách đăng ký'),
                    'controller' => 'Exports',
                    'action' => 'index'
                ],
                [ // Active
                    'name' => __('Danh sách hoạt động'),
                    'controller' => 'Exports',
                    'action' => 'exportActive'
                ],
                [ // Huyr
                    'name' => __('Danh sách hủy'),
                    'controller' => 'Exports',
                    'action' => 'exportAbort'
                ],
            ]
        ],
        [ // Import
            'name' => __('Import dữ liệu'),
            'icon' => 'fa fa-tasks',
            'child' => [
                [ // Đăng ký, hủy
                    'name' => __('Lịch sử import'),
                    'controller' => 'PlayerImports',
                    'action' => 'index'
                ],
                [ // Lịch sử
                    'name' => __('Import dữ liệu'),
                    'controller' => 'PlayerImports',
                    'action' => 'processCsv'
                ],
            ]
        ],
        
        [ // Bao cao
            'name' => __('Reports'),
            'icon' => 'fa fa-pie-chart',
            'child' => [
                [
                    'controller' => 'DailyKpiReport',
                    'action' => 'index',
                    'name' => __('Báo cáo doanh thu ngày')
                ],
                [
                    'controller' => 'DailyKpiReport',
                    'action' => 'subscriber',
                    'name' => __('Báo cáo thông tin thuê bao ngày')
                ],
                [
                    'controller' => 'WeeklyKpiReport',
                    'action' => 'index',
                    'name' => __('Báo cáo doanh thu tuần')
                ],
                [
                    'controller' => 'WeeklyKpiReport',
                    'action' => 'subscriber',
                    'name' => __('Báo cáo thông tin thuê bao tuần')
                ],
                [
                    'controller' => 'MonthlyKpiReport',
                    'action' => 'index',
                    'name' => __('Báo cáo doanh thu tháng')
                ],
                [
                    'controller' => 'MonthlyKpiReport',
                    'action' => 'subscriber',
                    'name' => __('Báo cáo thông tin thuê bao tháng')
                ],
                [
                    'controller' => 'QuarterlyKpiReport',
                    'action' => 'index',
                    'name' => __('Báo cáo doanh thu quý')
                ],
                [
                    'controller' => 'QuarterlyKpiReport',
                    'action' => 'subscriber',
                    'name' => __('Báo cáo thông tin thuê bao quý')
                ],
                [
                    'controller' => 'YearlyKpiReport',
                    'action' => 'index',
                    'name' => __('Báo cáo doanh thu năm')
                ],
                [
                    'controller' => 'YearlyKpiReport',
                    'action' => 'subscriber',
                    'name' => __('Báo cáo thông tin thuê bao năm')
                ],
                [
                    'controller' => 'TrackingReports',
                    'action' => 'index',
                    'name' => __('Báo cáo truy nhập ngày')
                ],
                [
                    'controller' => 'TraditionalReports',
                    'action' => 'index',
                    'name' => __('Báo cáo truyền thông ngày')
                ],
                [
                    'controller' => 'TraditionalReports',
                    'action' => 'weeklyReport',
                    'name' => __('Báo cáo truyền thông tuần')
                ],
                [
                    'controller' => 'TraditionalReports',
                    'action' => 'monthlyReport',
                    'name' => __('Báo cáo truyền thông tháng')
                ],
                [
                    'controller' => 'TraditionalReports',
                    'action' => 'quarterlyReport',
                    'name' => __('Báo cáo truyền thông quý')
                ],
                [
                    'controller' => 'TraditionalReports',
                    'action' => 'yearlyReport',
                    'name' => __('Báo cáo truyền thông năm')
                ],
                [
                    'controller' => 'DailyKpiInteraction',
                    'action' => 'mt',
                    'name' => __('daily_kpi_interaction_mt_title'),
                ],
                [
                    'controller' => 'DailyKpiInteraction',
                    'action' => 'charge',
                    'name' => __('daily_kpi_interaction_charge_title'),
                ],
                [
                    'controller' => 'DailyKpiInteraction',
                    'action' => 'msisdn',
                    'name' => __('daily_kpi_interaction_msisdn_title'),
                ],
            ]
        ],
        [ // Quan ly khach hang
            'name' => __('nav_customers'),
            'icon' => 'fa fa-users',
            'child' => [
                [ // Nguoi dung
                    'controller' => 'Visitors',
                    'action' => 'index',
                    'name' => __('visitor_nav_title'),
                ],
                [ // Danh gia
                    'controller' => 'Visitors',
                    'action' => 'index',
                    'name' => __('feedback_nav_title'),
                ],
                [ // Hoi dap
                    'name' => __('faq_nav_title'),
                    'controller' => 'Faqs',
                    'action' => 'index'
                ]
            ]
        ],
        [ // Quan ly Users
            'name' => __('nav_user'),
            'icon' => 'fa fa-user',
            'child' => [
                [ // Quản lý user
                    'name' => __('nav_user'),
                    'controller' => 'Users',
                    'action' => 'index'
                ],
//                [ // Bao Loi
//                    'name' => __('report_nav_title'),
//                    'controller' => 'Users',
//                    'action' => 'index',
//                ],
//                [ // Rating
//                    'name' => __('rating_nav_title'),
//                    'controller' => 'Users',
//                    'action' => 'index',
//                ]
            ]
        ]
    ],
    'Lang' => [
        'vi' => __('Vietnamese'),
        'en' => __('English'),
    ]
];
