// 1. Tampilkan customer yang memiliki total pembelian tertinggi. 
select customer_id, total_amount from orders order by total_amount desc limit 1;

// 2.Ranking gaji karyawan per departemen. 
select department, name, salary, RANK() OVER (PARTITION BY department ORDER BY salary desc) AS dept_rank from employees order by department, salary desc;

// 3.Status pembayaran pesanan. 
SELECT
    payments.order_id,
    orders.total_amount,
    payments.paid_amount,
    CASE 
        WHEN orders.total_amount <= payments.paid_amount THEN 'Lunas'
        ELSE 'Belum Lunas'
    END AS status
FROM payments
LEFT JOIN orders ON orders.id = payments.order_id;

// 4.Tampilkan customer dengan total pembelian di atas rata-rata. 
SELECT 
    c.name AS customer_name,
    SUM(se.quantity * p.price) AS total_spent
FROM sales_extended se
LEFT JOIN customers c ON c.id = se.customer_id
LEFT JOIN products p ON p.id = se.product_id
GROUP BY se.customer_id, c.name
HAVING SUM(se.quantity * p.price) > (
    SELECT AVG(total_per_customer)
    FROM (
        SELECT SUM(se2.quantity * p2.price) AS total_per_customer
        FROM sales_extended se2
        LEFT JOIN products p2 ON p2.id = se2.product_id
        GROUP BY se2.customer_id
    ) avg_table
)
ORDER BY total_spent DESC;

// 5.Total kumulatif penjualan per customer berdasarkan tanggal. 
SELECT 
    c.name AS customer_name,
    se.sale_date,
    SUM(se.quantity * p.price) AS daily_total,
    SUM(SUM(se.quantity * p.price)) OVER (
        PARTITION BY se.customer_id 
        ORDER BY se.sale_date ASC
    ) AS running_total
FROM sales_extended se
LEFT JOIN customers c ON c.id = se.customer_id
LEFT JOIN products p ON p.id = se.product_id
GROUP BY se.customer_id, c.name, se.sale_date
ORDER BY c.name, se.sale_date;

// 6.Tampilkan 2 produk terlaris per kategori. 
SELECT 
    category,
    product_name,
    total_qty,
    rank_in_category
FROM (
    SELECT 
        p.category,
        p.name AS product_name,
        SUM(se.quantity) AS total_qty,
        RANK() OVER (
            PARTITION BY p.category 
            ORDER BY SUM(se.quantity) DESC
        ) AS rank_in_category
    FROM sales_extended se
    LEFT JOIN products p ON p.id = se.product_id
    GROUP BY p.category, p.id, p.name
) ranked
WHERE rank_in_category <= 2
ORDER BY category, rank_in_category;