1)Retrieve all submissions with more than 2 trustees (“will.trustees” array) in answers JSON column)

SELECT *FROM submissions
WHERE JSON_TYPE(JSON_EXTRACT(answers, '$.will.trustees')) = 'ARRAY'
AND JSON_LENGTH(JSON_EXTRACT(answers, '$.will.trustees')) > 2;

2) To get Sum of all monetary gifts (“will.monetry_gifts”)accross all submissions where company_id = 2

Assuming monetry_gifts is an array of objects like: [{"amount": 100, "to":"Alice"}, ...]

SELECT SUM(CAST(JSON_EXTRACT(gift, '$.amount') AS DECIMAL(18,2))) AS total_gifts
FROM submissions s,
JSON_TABLE(s.answers, '$.will.monetry_gifts[*]' COLUMNS(gift JSON PATH '$')) AS jt
WHERE s.company_id = 2;

3)To get the Sum of monetary gifts given to charities in submission with ID = 1

 Assuming each gift object has a field "is_charity": true/false (or "type":"charity")

SELECT SUM(CAST(JSON_EXTRACT(gift, '$.amount') AS DECIMAL(18,2))) AS charity_total
FROM submissions s,
JSON_TABLE(s.answers, '$.will.monetry_gifts[*]' COLUMNS(
  gift JSON PATH '$',
  is_charity VARCHAR(10) PATH '$.is_charity',
  type VARCHAR(50) PATH '$.type'
)) AS jt
WHERE s.id = '1'
  AND (
    JSON_EXTRACT(gift, '$.is_charity') = TRUE
    OR LOWER(JSON_EXTRACT(gift, '$.type')) = '"charity"'
  );

 4) Eloquent: submissions with 2 or more female trustees
 We'll filter where trustees array entries have `gender = "female"`
 (Eloquent approach shown in eloquent_examples.php)
