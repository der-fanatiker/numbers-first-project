function checkInfo()
		{
			//var $login = document.form.input 
			var login = document.auth['login'].value
			var password = document.auth['password'].value;
			var loginstatus =false;
			var passwordstatus = false;
			if((/^[a-zA-Z0-9]+$/).test(login))
			{
				//Пропускаем типа
				loginstatus = true;
			}
			else
			{
			    //Выводим предупреждение и делаем фокус на логин
				alert("Вы ввели недопустимые символы в поле Логин.\n Пожалуйста, проверьте раскладку клавиатуры и переключите ее на английский язык");
				document.auth['login'].focus();

			}
			if((/^[a-zA-Z0-9]+$/).test(password))
			{
				//Пропускаем типа
				passwordstatus = true;
			}
			else
			{
				//Выводим предупреждение и делаем фокус на логин
				alert("Вы ввели недопустимые символы в поле Пароль.\n Пожалуйста, проверьте раскладку клавиатуры и переключите ее на английский язык");
				document.auth['password'].focus();
			}
			var status = checkFields();
			if((loginstatus==true)&&(passwordstatus==true)&&(status==true))
			{
				
				return true;
			}
			else
			{
				
				return false;
			}
		}
		function checkFields()
		{
			var elem = document.getElementsByTagName("input");
			var status1 = 0;
			var status2 = 0;
			for(var i=0;i<elem.length;i++)
			{
				if(elem[i].value!='')
				{
					status1 ++;
				}
				status2++;
			}
			if(status2 != status1)
			{
				alert("Вы заполнили не все поля");
				return false;
			}
			else
			{
				return true;
			}
			
		}
		function numberCheck()
		{
			var elem = document.getElementById("firstnumber");
			var status = false;
			if((/^[a-zA-Zа-яА-я0-9]+$/).test(elem.value))
			{
				status = true;
			}
			else
			{
				alert("Введите начальный номер");
				elem.focus();
				status = false;
			}
			if((/[0-9]/).test(elem.value))
			{
				status = true;
			}
			else
			{
				alert("Введите начальный номер, в цифровом формате");
				elem.focus();
				status = false;
			}
			if((status == true)&&(confirm("Вы точно уверенны, что хотите задать начальный номер")))
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		function commentCheck()
		{
			status = false;
			elem = document.getElementById("comment");
			if(elem.value == "")
			{
				alert("Заполните текстовое поле!");
			}
			else
			{
				status = true;
			}
			return status;
		}