
-- Usuário Pessoa Física (senha: password)
INSERT INTO users (type, name, email, password, cpf, birth_date, phone) VALUES
('pf', 'Davi Gomes', 'davi@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '123.456.789-00', '1990-05-15', '(11) 99999-0001'),
('pf', 'Jaqueline Lemes', 'jaqueline@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '987.654.321-00', '1985-08-20', '(11) 99999-0002');

-- Usuário Pessoa Jurídica (senha: password)
INSERT INTO users (type, name, email, password, cnpj, company_name, trade_name, phone) VALUES
('pj', 'Ana Costa', 'empresa@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '12.345.678/0001-99', 'Tech Solutions LTDA', 'TechSol', '(11) 3333-4444'),
('pj', 'Carlos Lima', 'distribuidora@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '98.765.432/0001-11', 'Distribuidora Boa Vista S.A.', 'Boa Vista', '(11) 5555-6666');

-- Produtos fictícios
INSERT INTO products (name, description, price, stock, category) VALUES
('Notebook Dell Inspiron 15', 'Notebook com processador Intel Core i5, 8GB RAM, SSD 256GB', 3499.90, 15, 'Eletrônicos'),
('Smartphone Samsung Galaxy A54', 'Smartphone 5G, 128GB, tela AMOLED 6.4"', 1899.00, 30, 'Eletrônicos'),
('Fone de Ouvido JBL Tune 510BT', 'Fone bluetooth com até 40h de bateria', 249.90, 50, 'Eletrônicos'),
('Mouse Logitech MX Master 3', 'Mouse sem fio ergonômico para produtividade', 499.00, 25, 'Periféricos'),
('Teclado Mecânico Redragon K552', 'Teclado mecânico gamer compacto RGB', 329.90, 20, 'Periféricos'),
('Monitor LG 24" Full HD', 'Monitor IPS 24 polegadas 75Hz', 999.00, 10, 'Periféricos'),
('Cadeira Gamer ThunderX3 EC3', 'Cadeira ergonômica com apoio lombar', 1299.00, 8, 'Móveis'),
('Webcam Logitech C920', 'Webcam Full HD 1080p com microfone', 599.00, 18, 'Periféricos'),
('SSD Kingston 480GB', 'SSD SATA III 2.5" leitura 500MB/s', 249.00, 40, 'Armazenamento'),
('Memória RAM Crucial 16GB DDR4', 'Módulo de memória DDR4 3200MHz', 299.90, 35, 'Componentes'),
('Roteador TP-Link Archer C6', 'Roteador dual band AC1200', 219.90, 22, 'Redes'),
('HD Externo Seagate 1TB', 'HD portátil USB 3.0 1 Terabyte', 359.00, 28, 'Armazenamento');
