export class ConciliacionView{

    async list_user_asig (users)  {
       let tr = '';
        users.forEach(user => {
            tr += `
            <tr>                    
                        <td>${user.name} ${user.lastname}</td>
                        <td>${user.email}</td>
                        
                        <td>Parte solicitada</td>
                    
                        
                        <td>4 May. 2023. 4:33 PM</td>
                        <td width="20%">
                                    
                            <button type="button" data-user="123" data-pivot="51" class="btn btn-danger btn-sm btn_delete_usuario_conciliacion">  
                            Eliminar
                            </button> 

                                    <button type="button" data-user="123" data-pivot="51" class="btn btn-warning btn-sm btn_sancionar_usuario_conciliacion">  
                                Sancionar
                            </button> 
                                    

                                    <button type="button" data-type="197" data-user="123" data-section="general" class="btn btn-primary btn-sm btn_asinar_usuario_conciliacion">  
                            Actualizar
                            </button>
                            <button type="button" data-type="197" data-user="123" data-section="general" class="btn btn-success btn-sm btn_detalles_us_con">  
                                Detalles
                                </button>            
    </td>
</tr>
            `;
        });

    }

}


