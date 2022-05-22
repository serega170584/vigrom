# Инструкция

#Разворачивание инфраструктуры

git clone https://github.com/serega170584/vigrom.git

Зайти в директорию vigrom

docker-compose up -d --build

Установить пакеты composer: docker-compose exec app composer install

Запустить миграции: docker-compose exec app php bin/console doctrine:migrations:migrate, при ответе на вопрос нажать Enter

Запустить workerы: docker-compose exec -u 0 app supervisord -c /app/messenger-worker.conf

#Метод изменения баланса:

http://localhost:3100/refill/{номер кошелька}

Пример параметров:
amount=100
type=debit
reason=stock
currency=USD

В ответе получаем id транзакции


#Метод получения баланса:

http://localhost:3100/balance/{номер кошелька}

В ответе получаем сумму в валюте кошелька

#SQL запрос, который вернет сумму, полученную по причине refund за последние 7 дней.

SELECT SUM(amount)
FROM transaction
WHERE reason = 'refund'
AND status = 'approved'
AND created_at >= CURRENT_TIMESTAMP - INTERVAL '7 days'