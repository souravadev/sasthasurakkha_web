INSERT INTO countries
    (
        iso_code_2,
        iso_code_3,
        phone_code,
        name,
        currency,
        continent
    ) 
VALUES
    ('IN', 'IND', '91', 'India', 'INR', 'Asia');






-- //Provinces | States
INSERT INTO provinces
    (
        country_id,
        name,
        code
    )
 VALUES
    (1, 'Andhra Pradesh', 'IN-AP'),
    (1, 'Arunachal Pradesh', 'IN-AR'),
    (1, 'Assam', 'IN-AS'),
    (1, 'Bihar', 'IN-BR'),
    (1, 'Chhattisgarh', 'IN-CT'),
    (1, 'Goa', 'IN-GA'),
    (1, 'Gujarat', 'IN-GJ'),
    (1, 'Haryana', 'IN-HR'),
    (1, 'Himachal Pradesh', 'IN-HP'),
    (1, 'Jharkhand', 'IN-JH'),
    (1, 'Karnataka', 'IN-KA'),
    (1, 'Kerala', 'IN-KL'),
    (1, 'Madhya Pradesh', 'IN-MP'),
    (1, 'Maharashtra', 'IN-MH'),
    (1, 'Manipur', 'IN-MN'),
    (1, 'Meghalaya', 'IN-ML'),
    (1, 'Mizoram', 'IN-MZ'),
    (1, 'Nagaland', 'IN-NL'),
    (1, 'Odisha', 'IN-OR'),
    (1, 'Punjab', 'IN-PB'),
    (1, 'Rajasthan', 'IN-RJ'),
    (1, 'Sikkim', 'IN-SK'),
    (1, 'Tamil Nadu', 'IN-TN'),
    (1, 'Telangana', 'IN-TG'),
    (1, 'Tripura', 'IN-TR'),
    (1, 'Uttar Pradesh', 'IN-UP'),
    (1, 'Uttarakhand', 'IN-UT'),
    (1, 'West Bengal', 'IN-WB');