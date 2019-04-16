SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

--
-- База данных: `schedule`
--

--
-- Структура таблицы `teachers`
--

CREATE TABLE `teachers` (
  `id` int(10) UNSIGNED NOT NULL,
  `full_name` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Структура таблицы `сlasses`
--

CREATE TABLE `classes` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=UTF8;

--
-- Структура таблицы `groups`
--

CREATE TABLE `groups` (
  `id` int(10) UNSIGNED NOT NULL,
  `number` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Структура таблицы `schedule`
--

CREATE TABLE `schedules` (
  `id` int(10) UNSIGNED NOT NULL,
  `group_id` int(10)  NULL,
  `teacher1_id` int(10)  NULL,
  `teacher2_id` int(10) NULL,
  `сlass_id` int(10)  NULL,
  `parity_week` int(10) NOT NULL,
  `day_of_the_week` text NOT NULL,
  `time_of_class` text NOT NULL

) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `teachers`
--
ALTER TABLE `teachers`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `classes`
--
ALTER TABLE `classes`
  ADD PRIMARY KEY (`id`);


--
-- Индексы таблицы `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `schedules`
--
ALTER TABLE `schedules`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `schedules`
  ADD FOREIGN KEY (`group_id`) REFERENCES `groups`(`id`)
  ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE `schedules`
  ADD FOREIGN KEY (`teacher1_id`) REFERENCES `teachers`(`id`)
  ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE `schedules`
  ADD FOREIGN KEY (`teacher2_id`) REFERENCES `teachers`(`id`)
  ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE `schedules`
  ADD FOREIGN KEY (`сlass_id`) REFERENCES `classes`(`id`)
  ON DELETE RESTRICT ON UPDATE RESTRICT;



--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `teachers`
--
ALTER TABLE `teachers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

--
-- AUTO_INCREMENT для таблицы `classes`
--
ALTER TABLE `classes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

--
-- AUTO_INCREMENT для таблицы `groups`
--
ALTER TABLE `groups`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

--
-- AUTO_INCREMENT для таблицы `schedules`
--
ALTER TABLE `schedules`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;