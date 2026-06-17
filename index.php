<?php
// Проверяем, есть ли статус в URL (после редиректа из send.php)
$status = isset($_GET['status']) ? $_GET['status'] : '';
$message = '';
if ($status === 'success') {
    $message = '<div style="background:#1a4a3a; color:#b0e0c0; padding:15px 20px; border-radius:40px; border-left:6px solid #4caf50; margin-bottom:20px;">✅ Ваша заявка успешно отправлена! Мы свяжемся с вами в ближайшее время.</div>';
} elseif ($status === 'error') {
    $message = '<div style="background:#4a1a1a; color:#f0b0b0; padding:15px 20px; border-radius:40px; border-left:6px solid #f44336; margin-bottom:20px;">❌ Произошла ошибка при отправке. Пожалуйста, попробуйте ещё раз или свяжитесь с нами по телефону.</div>';
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Аренда дома в Архызе | Сруб с баней</title>
    <style>
        /* Все стили полностью сохранены, как в предыдущей версии */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Roboto, system-ui, -apple-system, sans-serif;
            background-color: #0b1a2f;
            color: #e0e8f0;
            line-height: 1.6;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        .header {
            background: linear-gradient(135deg, #0a1a2b, #1a2f4a);
            padding: 20px 0 10px 0;
            border-bottom: 2px solid #2a4b6e;
        }
        .header-image {
            width: 100%;
            height: 300px;
            background: url('https://via.placeholder.com/1200x300/1a3a5a/8ab4d8?text=Дом+из+сруба+в+Архызе') no-repeat center center/cover;
            border-radius: 16px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.6);
            margin-bottom: 15px;
        }
        .header h1 {
            font-size: 2.4rem;
            font-weight: 300;
            letter-spacing: 2px;
            color: #c0daf0;
            text-shadow: 0 2px 8px rgba(0,0,0,0.7);
            text-align: center;
        }
        .header .subtitle {
            text-align: center;
            font-size: 1.1rem;
            color: #8ab4d8;
            margin-top: 5px;
            border-top: 1px solid #2a4b6e;
            padding-top: 10px;
        }
        .description {
            background: rgba(20, 40, 60, 0.6);
            backdrop-filter: blur(4px);
            border-radius: 12px;
            padding: 25px 30px;
            margin: 25px 0 35px 0;
            border-left: 4px solid #4a8bb7;
            box-shadow: 0 4px 16px rgba(0,0,0,0.3);
        }
        .description p {
            font-size: 1.1rem;
            color: #d0dce8;
        }
        .rooms-section {
            margin: 40px 0;
        }
        .rooms-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 30px;
            justify-content: center;
        }
        .room-card {
            background: #122b44;
            border-radius: 16px;
            padding: 20px;
            flex: 1 1 280px;
            max-width: 350px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.5);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            border: 1px solid #2a4b6e;
            display: flex;
            flex-direction: column;
        }
        .room-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 32px rgba(0,20,40,0.8);
            border-color: #4a8bb7;
        }
        .room-card h3 {
            font-size: 1.8rem;
            font-weight: 300;
            color: #b8d8f0;
            margin-bottom: 8px;
            letter-spacing: 1px;
        }
        .room-price {
            font-size: 1.3rem;
            color: #8fcbff;
            background: #0a1a2b;
            display: inline-block;
            padding: 4px 16px;
            border-radius: 30px;
            margin-bottom: 15px;
            border: 1px solid #2a5a7a;
        }
        .room-desc {
            color: #b0c8d8;
            font-size: 0.95rem;
            margin-bottom: 20px;
            flex-grow: 1;
        }
        .btn-select {
            background: #1a4a6a;
            border: none;
            color: white;
            padding: 12px 0;
            font-size: 1rem;
            font-weight: 600;
            border-radius: 40px;
            cursor: pointer;
            transition: background 0.2s, transform 0.1s;
            letter-spacing: 1px;
            border: 1px solid #3a7ba0;
            margin-top: 10px;
        }
        .btn-select:hover {
            background: #2a6a8a;
            transform: scale(1.02);
        }
        .btn-select:active {
            transform: scale(0.97);
        }
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.7);
            backdrop-filter: blur(6px);
            z-index: 1000;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        .modal-overlay.active {
            display: flex;
        }
        .modal-content {
            background: #0f263b;
            border-radius: 24px;
            max-width: 700px;
            width: 100%;
            padding: 30px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.8);
            border: 1px solid #2a5a7a;
            position: relative;
            max-height: 90vh;
            overflow-y: auto;
        }
        .modal-close {
            position: absolute;
            top: 15px;
            right: 20px;
            font-size: 2rem;
            color: #8ab4d8;
            cursor: pointer;
            background: none;
            border: none;
            transition: color 0.2s;
        }
        .modal-close:hover {
            color: #c0e0ff;
        }
        .modal-content h2 {
            font-size: 2rem;
            font-weight: 300;
            color: #b8d8f0;
            margin-bottom: 15px;
            border-bottom: 1px solid #2a4b6e;
            padding-bottom: 10px;
        }
        .modal-gallery {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin: 20px 0;
        }
        .modal-gallery img {
            width: calc(50% - 6px);
            border-radius: 12px;
            border: 1px solid #2a4b6e;
            background: #1a3a5a;
            height: 150px;
            object-fit: cover;
        }
        .modal-description {
            color: #c0d8e8;
            font-size: 1rem;
            margin: 15px 0;
            background: #0a1a2b;
            padding: 15px;
            border-radius: 12px;
            border-left: 3px solid #4a8bb7;
        }
        .booking-section {
            background: #0f263b;
            border-radius: 20px;
            padding: 35px 30px;
            margin: 50px 0;
            border: 1px solid #1a4a6a;
            box-shadow: 0 8px 24px rgba(0,0,0,0.4);
        }
        .booking-section h2 {
            font-size: 2rem;
            font-weight: 300;
            color: #b8d8f0;
            margin-bottom: 25px;
            text-align: center;
        }
        .form-group {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 20px;
        }
        .form-group > * {
            flex: 1 1 200px;
        }
        .form-group label {
            display: block;
            margin-bottom: 6px;
            font-weight: 500;
            color: #8ab4d8;
            font-size: 0.9rem;
            letter-spacing: 0.5px;
        }
        .form-group input, 
        .form-group select, 
        .form-group textarea {
            width: 100%;
            padding: 12px 16px;
            background: #0a1a2b;
            border: 1px solid #2a4b6e;
            border-radius: 40px;
            color: #e0e8f0;
            font-size: 1rem;
            outline: none;
            transition: border 0.2s, box-shadow 0.2s;
        }
        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            border-color: #4a8bb7;
            box-shadow: 0 0 0 3px rgba(74, 139, 183, 0.3);
        }
        .form-group textarea {
            border-radius: 20px;
            resize: vertical;
            min-height: 80px;
        }
        .form-group input::placeholder,
        .form-group textarea::placeholder {
            color: #5a7a92;
        }
        .form-row {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }
        .form-row .form-group {
            flex: 1 1 180px;
        }
        .btn-submit {
            background: #1a5a7a;
            border: none;
            color: white;
            padding: 16px 40px;
            font-size: 1.2rem;
            font-weight: 600;
            border-radius: 60px;
            cursor: pointer;
            transition: background 0.2s, transform 0.1s;
            letter-spacing: 1.5px;
            border: 1px solid #4a8bb7;
            width: 100%;
            margin-top: 10px;
        }
        .btn-submit:hover {
            background: #2a7a9a;
            transform: scale(1.01);
        }
        .contacts {
            background: #0a1a2b;
            border-radius: 16px;
            padding: 25px 30px;
            margin: 30px 0;
            border: 1px solid #1a4a6a;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
        }
        .contacts-item {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 1.1rem;
            color: #c0d8e8;
        }
        .contacts-item a {
            color: #8fcbff;
            text-decoration: none;
            transition: color 0.2s;
        }
        .contacts-item a:hover {
            color: #b8e0ff;
            text-decoration: underline;
        }
        .contacts-item .icon {
            font-size: 1.6rem;
        }
        .rules {
            background: #0f263b;
            border-radius: 16px;
            padding: 20px 30px;
            margin: 30px 0 50px 0;
            border: 1px solid #1a4a6a;
            font-size: 0.95rem;
            color: #b0c8d8;
        }
        .rules h3 {
            color: #8ab4d8;
            font-weight: 400;
            letter-spacing: 1px;
            margin-bottom: 10px;
        }
        .rules ul {
            list-style: none;
            padding-left: 0;
        }
        .rules ul li {
            padding: 4px 0;
            border-bottom: 1px solid #1a3a52;
        }
        .rules ul li:last-child {
            border-bottom: none;
        }
        @media (max-width: 700px) {
            .header-image {
                height: 180px;
            }
            .header h1 {
                font-size: 1.8rem;
            }
            .modal-gallery img {
                width: 100%;
                height: 130px;
            }
            .contacts {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
            .room-card {
                flex: 1 1 100%;
                max-width: 100%;
            }
        }
        @media (max-width: 480px) {
            .header h1 {
                font-size: 1.4rem;
            }
            .description p {
                font-size: 0.95rem;
            }
            .booking-section {
                padding: 20px 15px;
            }
        }
    </style>
</head>
<body>
    <div class="container">

        <header class="header">
            <div class="header-image"></div>
            <h1>🏔️ Дом в Архызе • Сруб с баней</h1>
            <div class="subtitle">Уют, горы, баня — идеальный отдых в любое время года</div>
        </header>

        <div class="description">
            <p>🌲 Дом из натурального сруба расположен в живописном уголке Архыза. 
            Вас ждёт тишина леса, чистый горный воздух и панорамные виды. 
            В доме есть всё для комфортного проживания: тёплые полы, камин, просторная терраса. 
            Отдельно — русская баня с вениками и ароматным чаем.</p>
        </div>

        <section class="rooms-section">
            <h2 style="font-weight:300; color:#b8d8f0; margin-bottom:25px; text-align:center; letter-spacing:1px;">Выберите вариант размещения</h2>
            <div class="rooms-grid">
                <div class="room-card">
                    <h3>Обычный</h3>
                    <div class="room-price">10 000 ₽ / сутки</div>
                    <div class="room-desc">Двухкомнатный номер с видом на лес. Всё необходимое для спокойного отдыха.</div>
                    <button class="btn-select" data-room="standard">Выбрать</button>
                </div>
                <div class="room-card">
                    <h3>Люкс</h3>
                    <div class="room-price">15 000 ₽ / сутки</div>
                    <div class="room-desc">Просторные апартаменты с панорамными окнами, камином и отдельной террасой.</div>
                    <button class="btn-select" data-room="lux">Выбрать</button>
                </div>
                <div class="room-card" style="border-color:#3a7ba0;">
                    <h3>Баня</h3>
                    <div class="room-price">3 000 ₽ / сеанс</div>
                    <div class="room-desc">Дополнительная услуга — русская баня на дровах. Веники, ароматный пар и купель.</div>
                    <button class="btn-select" data-room="banya">Выбрать</button>
                </div>
            </div>
        </section>

        <div class="modal-overlay" id="modal">
            <div class="modal-content">
                <button class="modal-close" id="modalClose">&times;</button>
                <h2 id="modalTitle">Название номера</h2>
                <div class="modal-gallery" id="modalGallery"></div>
                <div class="modal-description" id="modalDesc">Описание номера.</div>
            </div>
        </div>

        <section class="booking-section" id="booking">
            <h2>📋 Забронировать</h2>
            <!-- ВЫВОД СООБЩЕНИЯ О СТАТУСЕ -->
            <?php echo $message; ?>
            <form id="bookingForm" action="send.php" method="POST">
                <div class="form-row">
                    <div class="form-group">
                        <label for="name">Имя *</label>
                        <input type="text" id="name" name="name" placeholder="Ваше имя" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email *</label>
                        <input type="email" id="email" name="email" placeholder="example@mail.ru" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="phone">Телефон *</label>
                        <input type="tel" id="phone" name="phone" placeholder="+7 988 110 93 45" required>
                    </div>
                    <div class="form-group">
                        <label for="checkin">Заезд</label>
                        <input type="date" id="checkin" name="checkin">
                    </div>
                    <div class="form-group">
                        <label for="checkout">Выезд</label>
                        <input type="date" id="checkout" name="checkout">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="roomSelect">Тип размещения</label>
                        <select id="roomSelect" name="roomType">
                            <option value="standard">Обычный (10 000 ₽)</option>
                            <option value="lux">Люкс (15 000 ₽)</option>
                            <option value="banya">Баня (3 000 ₽)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="guests">Количество гостей</label>
                        <input type="number" id="guests" name="guests" min="1" value="2">
                    </div>
                </div>
                <div class="form-group">
                    <label for="comment">Комментарий</label>
                    <textarea id="comment" name="comment" placeholder="Дополнительные пожелания..."></textarea>
                </div>
                <button type="submit" class="btn-submit">Отправить заявку</button>
            </form>
        </section>

        <div class="contacts">
            <div class="contacts-item">
                <span class="icon">📞</span>
                <a href="tel:+79881109345">+7 988 110 93 45</a>
            </div>
            <div class="contacts-item">
                <span class="icon">✈️</span>
                <a href="https://t.me/AkimShtanyuk" target="_blank">@AkimShtanyuk</a>
            </div>
            <div class="contacts-item">
                <span class="icon">📍</span>
                <span>Архыз, ул. Горная, 12</span>
            </div>
        </div>

        <div class="rules">
            <h3>📌 Правила проживания</h3>
            <ul>
                <li>• Заезд после 14:00, выезд до 12:00.</li>
                <li>• Курение строго запрещено внутри дома.</li>
                <li>• Животные допускаются по предварительному согласованию (доп. плата 500 ₽/сутки).</li>
                <li>• Баня топится только по предварительной договорённости.</li>
                <li>• При порче имущества взымается стоимость восстановления.</li>
                <li>• Тишина с 22:00 до 08:00.</li>
            </ul>
        </div>

    </div>

    <script>
        // Модальное окно (без изменений)
        const roomsData = {
            standard: {
                title: 'Обычный',
                price: '10 000 ₽ / сутки',
                description: 'Уютный двухкомнатный номер с видом на лес. В номере: двуспальная кровать, гостиная с диваном, телевизор, чайная станция, санузел с душем. Идеально для пары или семьи с одним ребёнком.',
                images: [
                    'https://via.placeholder.com/400x300/1a3a5a/8ab4d8?text=Обычный+номер+1',
                    'https://via.placeholder.com/400x300/1a3a5a/8ab4d8?text=Обычный+номер+2'
                ]
            },
            lux: {
                title: 'Люкс',
                price: '15 000 ₽ / сутки',
                description: 'Просторные апартаменты с панорамным остеклением. Гостиная с камином, отдельная спальня, большая терраса с видом на горы. Ванная с джакузи. Максимальный комфорт для взыскательных гостей.',
                images: [
                    'https://via.placeholder.com/400x300/1a4a6a/8fcbff?text=Люкс+вид+1',
                    'https://via.placeholder.com/400x300/1a4a6a/8fcbff?text=Люкс+вид+2'
                ]
            },
            banya: {
                title: 'Баня (доп. услуга)',
                price: '3 000 ₽ / сеанс',
                description: 'Русская парная из липы с ароматными вениками. После парной — купель с ледяной водой и зона отдыха с чаем на травах. Сеанс до 2 часов. Рекомендуем бронировать заранее.',
                images: [
                    'https://via.placeholder.com/400x300/1a3a5a/8ab4d8?text=Баня+интерьер',
                    'https://via.placeholder.com/400x300/1a3a5a/8ab4d8?text=Баня+купель'
                ]
            }
        };

        const modal = document.getElementById('modal');
        const modalClose = document.getElementById('modalClose');
        const modalTitle = document.getElementById('modalTitle');
        const modalGallery = document.getElementById('modalGallery');
        const modalDesc = document.getElementById('modalDesc');

        function openModal(roomKey) {
            const data = roomsData[roomKey];
            if (!data) return;
            modalTitle.textContent = data.title + ' — ' + data.price;
            modalDesc.textContent = data.description;
            modalGallery.innerHTML = '';
            data.images.forEach(src => {
                const img = document.createElement('img');
                img.src = src;
                img.alt = data.title;
                modalGallery.appendChild(img);
            });
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            modal.classList.remove('active');
            document.body.style.overflow = '';
        }

        document.querySelectorAll('.btn-select').forEach(btn => {
            btn.addEventListener('click', function(e) {
                const room = this.getAttribute('data-room');
                openModal(room);
            });
        });

        modalClose.addEventListener('click', closeModal);
        modal.addEventListener('click', function(e) {
            if (e.target === modal) closeModal();
        });
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && modal.classList.contains('active')) {
                closeModal();
            }
        });

        // Валидация формы перед отправкой (оставляем, но не блокируем отправку)
        document.getElementById('bookingForm').addEventListener('submit', function(e) {
            const name = document.getElementById('name').value.trim();
            const email = document.getElementById('email').value.trim();
            const phone = document.getElementById('phone').value.trim();
            if (!name || !email || !phone) {
                e.preventDefault();
                alert('Пожалуйста, заполните обязательные поля: Имя, Email и Телефон.');
                return false;
            }
            // Если всё ок, форма отправится на send.php
            return true;
        });

        // Автозаполнение дат
        const today = new Date();
        const tomorrow = new Date(today);
        tomorrow.setDate(tomorrow.getDate() + 1);
        const formatDate = (d) => d.toISOString().split('T')[0];
        document.getElementById('checkin').value = formatDate(today);
        document.getElementById('checkout').value = formatDate(tomorrow);
    </script>
</body>
</html>