# Instruction


# Infrastructure deploy 

    git clone https://github.com/serega170584/vigrom.git

Visit vigrom directory

    docker-compose up -d --build

Install composer packages: 
    
    docker-compose exec app composer install

Migrations running:

    docker-compose exec app php bin/console doctrine:migrations:migrate, при ответе на вопрос нажать Enter

Workers running: 

    docker-compose exec -u 0 app supervisord -c /app/messenger-worker.conf

# Update balance method:

    http://localhost:3100/refill/{wallet number}

Parameters example:
amount=100
type=debit
reason=stock
currency=USD

Response sends transaction id


# Get balance method:

http://localhost:3100/balance/{wallet number}

Response sends sum in wallet currency 

#SQL query, which return sum, delivered for reason refund for last 7 days.

SELECT SUM(amount)
FROM transaction
WHERE reason = 'refund'
AND status = 'approved'
AND created_at >= CURRENT_TIMESTAMP - INTERVAL '7 days'