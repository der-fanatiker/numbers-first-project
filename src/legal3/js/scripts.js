function checkInfo()
		{
			//var $login = document.form.input 
			var login = document.auth['login'].value
			var password = document.auth['password'].value;
			var loginstatus =false;
			var passwordstatus = false;
			if((/^[a-zA-Z0-9]+$/).test(login))
			{
				//���������� ����
				loginstatus = true;
			}
			else
			{
			    //������� �������������� � ������ ����� �� �����
				alert("�� ����� ������������ ������� � ���� �����.\n ����������, ��������� ��������� ���������� � ����������� �� �� ���������� ����");
				document.auth['login'].focus();

			}
			if((/^[a-zA-Z0-9]+$/).test(password))
			{
				//���������� ����
				passwordstatus = true;
			}
			else
			{
				//������� �������������� � ������ ����� �� �����
				alert("�� ����� ������������ ������� � ���� ������.\n ����������, ��������� ��������� ���������� � ����������� �� �� ���������� ����");
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
				alert("�� ��������� �� ��� ����");
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
			if((/^[a-zA-Z�-��-�0-9]+$/).test(elem.value))
			{
				status = true;
			}
			else
			{
				alert("������� ��������� �����");
				elem.focus();
				status = false;
			}
			if((/[0-9]/).test(elem.value))
			{
				status = true;
			}
			else
			{
				alert("������� ��������� �����, � �������� �������");
				elem.focus();
				status = false;
			}
			if((status == true)&&(confirm("�� ����� ��������, ��� ������ ������ ��������� �����")))
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
				alert("��������� ��������� ����!");
			}
			else
			{
				status = true;
			}
			return status;
		}