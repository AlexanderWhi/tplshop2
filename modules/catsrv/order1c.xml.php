<���������������������� �����������="2.05" ����������������="<?=$make_date?>T<?=$make_time?>" ����������="��=yyyy-MM-dd; ���=DT" �������������="��=��:��:��; ���=T" ��������������������="T" �����������="��=18; ���=2; ���=." ����������������="��=18; ���=2; ���=.">
<?foreach ($document as $d) {?>
		<��������>
				<��><?=$d['id']?></��>
				<�����><?=$d['num']?></�����>
				<����><?=$d['date']?></����>
				<�����������>����� ������</�����������>
				<����>��������</����>
				<������>���</������>
				<����>1</����>
				<�����><?=$d['summ']?></�����>
				<�����������>
					<����������>
						<��><?=$d['contragent']['id']?></��>
						<������������><?=$d['contragent']['name']?></������������>
														<������������������><?=$d['contragent']['name']?></������������������>
								<�������></�������><���></���>								<����������������>
									<�������������><?=$d['contragent']['address']?></�������������><������������>
										<���>�������� ������</���>
										<��������></��������>
									</������������><������������>
										<���>������</���>
										<��������>������������</��������>
									</������������><������������>
										<���>�����</���>
										<��������>������������</��������>
									</������������><������������>
										<���>�����</���>
										<��������><?=$d['contragent']['address']?></��������>
									</������������>								</����������������>
														<��������>
																	<�������>
										<���>�����</���>
										<��������><?=$d['contragent']['mail']?></��������>
									</�������>
																</��������>
														<�������������>
								<�������������>
									<����������>
										<���������>���������� ����</���������>
										<��></��>
										<������������></������������>
									</����������>
								</�������������>
							</�������������>
												
						<����>����������</����>						
					</����������>
				</�����������>
				
				<�����><?=$d['time']?></�����>
				<�����������><?=$d['additionally']?></�����������>
						<������> 
						<?foreach ($d['goods'] as $g) {?>
									<�����>
						<��><?=$g['id']?></��>
						<����������><?//=$g['cat_id']?></����������>
						<������������><?=$g['name']?></������������>
						<�������������� ���="796" ������������������="�����" �����������������������="PCE">��</��������������>
													<�������������>
								<������������>
									<������������>���</������������>
									<������>18</������>
								</������������>
							</�������������>
													<�������������><?=$g['price']?></�������������>
						<����������><?=$g['count']?></����������>
						<�����><?=$g['summ']?></�����>
						<������������������>
							<�����������������>
								<������������>���������������</������������>
								<��������>�����</��������>
							</�����������������>
							<�����������������>
								<������������>���������������</������������>
								<��������>�����</��������>
							</�����������������>
						</������������������>
													<?/*������>
								<�����>
									<������������>���</������������>
									<������������>true</������������>
									<�����>23.94</�����>
								</�����>
							</������*/?>
												</�����>
												
												
						<?} ?>
										
									</������>
				<������������������>
											<�����������������>
							<������������>����� ������</������������>
							<��������>�������� ������</��������>
						</�����������������>
												<�����������������>
							<������������>������ ��������</������������>
							<��������>����������</��������>
						</�����������������>
											<�����������������>
						<������������>����� �������</������������>
						<��������>false</��������>
					</�����������������>
					<�����������������>
						<������������>�������� ���������</������������>
						<��������>false</��������>
					</�����������������>
					<�����������������>
						<������������>�������</������������>
						<��������>false</��������>
					</�����������������>
					<�����������������>
						<������������>��������� ������</������������>
						<��������>false</��������>
					</�����������������>
					<�����������������>
						<������������>������ ������</������������>
						<��������>[N] ������</��������>
					</�����������������>
					
											<�����������������>
							<������������>���� ��������� �������</������������>
							<��������><?/*2011-10-13 15:45:59*/?></��������>
						</�����������������>
											<�����������������>
						<������������>����</������������>
						<��������>[s1] �������</��������>
					</�����������������>
				</������������������>
			</��������>
			
			<?}?>
						
					</����������������������>
		