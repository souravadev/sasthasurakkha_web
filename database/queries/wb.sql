

-- Counties | Districts | West Bengal
INSERT INTO counties
    (
        province_id,
        name,
        code
    )
VALUES
    (28, 'Alipurduar', 'WB-ALP'),
    (28, 'Bankura', 'WB-BNK'),
    (28, 'Birbhum', 'WB-BRB'),
    (28, 'Cooch Behar', 'WB-COB'),
    (28, 'Dakshin Dinajpur (South Dinajpur)', 'WB-DDJ'),
    (28, 'Darjeeling', 'WB-DRJ'),
    (28, 'Hooghly', 'WB-HGL'),
    (28, 'Howrah', 'WB-HWR'),
    (28, 'Jalpaiguri', 'WB-JPG'),
    (28, 'Jhargram', 'WB-JHR'),
    (28, 'Kalimpong', 'WB-KLP'),
    (28, 'Kolkata', 'WB-KOL'),
    (28, 'Maldah (Malda)', 'WB-MLD'),
    (28, 'Murshidabad', 'WB-MSD'),
    (28, 'Nadia', 'WB-NAD'),
    (28, 'North 24 Parganas', 'WB-N24'),
    (28, 'Paschim Bardhaman (West Bardhaman)', 'WB-PBD'),
    (28, 'Paschim Medinipur (West Medinipur)', 'WB-PMD'),
    (28, 'Purba Bardhaman (East Bardhaman)', 'WB-EBD'),
    (28, 'Purba Medinipur (East Medinipur)', 'WB-EMD'),
    (28, 'Purulia', 'WB-PRL'),
    (28, 'South 24 Parganas', 'WB-S24'),
    (28, 'Uttar Dinajpur (North Dinajpur)', 'WB-UDJ');


-- Wards | West Bengal
-- Wards for Paschim Medinipur (county_id = 18)
INSERT INTO wards 
    (county_id, name, code)
VALUES
    (18, 'Debra', 'DBR'),
    (18, 'Jalchak', 'JLC'),
    (18, 'Maligram', 'MLG'),
    (18, 'Pingla', 'PNL'),
    (18, 'Sabang', 'SBN'),
    (18, 'Ghatal', 'GHT'),
    (18, 'Kharagpur', 'KGP'),
    (18, 'Medinipur', 'MDN'),
    (18, 'Dantan', 'DTN');


-- Wards for Purba Medinipur (county_id = 20)
INSERT INTO wards 
    (county_id, name, code)
VALUES
    (20, 'Haldia', 'HAL'),
    (20, 'Digha', 'DGH'),
    (20, 'Contai', 'CNT'),
    (20, 'Ramnagar', 'RMN'),
    (20, 'Tamluk', 'TML'),
    (20, 'Egra', 'EGR'),
    (20, 'Mecheda', 'MCD'),
    (20, 'Panskura', 'PNK'),
    (20, 'Haur', 'HAU'),
    (20, 'Moyna', 'MOY'),
    (20, 'Bolaipanda', 'BOL');

-- Wards for Jharagram (county_id = 10)
INSERT INTO wards
    (county_id, name, code)
VALUES
    (10, 'Jhargram Town', 'JGT');