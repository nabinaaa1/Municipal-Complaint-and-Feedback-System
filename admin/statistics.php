<?php
// Average rating
$result = $conn->query("SELECT AVG(rating) AS avg_rating FROM feedback");
$row = $result->fetch_assoc();
echo "Average Rating: " . number_format($row['avg_rating'], 2) . "<br>";

// Most complained category
$result = $conn->query("SELECT complaints.category, COUNT(*) AS total 
                        FROM complaints 
                        JOIN feedback ON complaints.id=feedback.complaint_id
                        GROUP BY complaints.category 
                        ORDER BY total DESC LIMIT 1");
$row = $result->fetch_assoc();
echo "Most Complained Category: " . ($row['category'] ?? 'N/A') . " ({$row['total']} feedbacks)<br>";
